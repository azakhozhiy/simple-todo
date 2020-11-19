<?php

declare(strict_types=1);

namespace App\Packages\Files\Managers;

use App\Packages\Core\Abstracts\CoreManager;
use App\Packages\Files\Models\File;
use Intervention\Image\ImageManager;
use PDO;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager extends CoreManager
{
    private ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->setModel(new File());
        $this->imageManager = $imageManager;
    }

    public function create(?int $creator_id, UploadedFile $file, string $dir): int
    {
        if (!is_dir($dir) && !mkdir($dir, 0770, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        $extension = mb_strtolower($file->getClientOriginalExtension());
        $mimetype = $file->getClientMimeType();
        $original_name = md5($file->getClientOriginalName());

        $file_path = $dir.$original_name.'.'.$extension;

        $this->imageManager->make($file)
            ->fit(File::MAX_WIDTH, File::MAX_HEIGHT)
            ->save($dir.md5((string) time()).'.'.$extension);

        return $this->record(static function (PDO $db, string $table) use (
            $creator_id,
            $original_name,
            $mimetype,
            $extension,
            $file_path
        ) {
            $sql = "INSERT INTO $table (original_name, mimetype, extension, file_path, creator_id) 
                                VALUES (:original_name, :mimetype, :extension, :file_path, :creator_id)";

            $query = $db->prepare($sql);

            $query->bindParam('original_name', $original_name, PDO::PARAM_STR);
            $query->bindParam('mimetype', $mimetype, PDO::PARAM_STR);
            $query->bindParam('extension', $extension, PDO::PARAM_STR);
            $query->bindParam('file_path', $file_path, PDO::PARAM_STR);
            $query->bindParam('creator_id', $creator_id, PDO::PARAM_INT);

            $query->execute();

            return (int) $db->lastInsertId();
        });
    }
}
