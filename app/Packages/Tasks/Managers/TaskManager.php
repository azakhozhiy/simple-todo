<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Managers;

use App\Packages\Core\Abstracts\CoreManager;
use App\Packages\Tasks\Models\Task;
use PDO;

class TaskManager extends CoreManager
{
    public function __construct()
    {
        $this->setModel(new Task());
    }

    public function createOrUpdate(
        ?int $task_id,
        ?int $creator_id,
        string $title,
        string $name,
        ?string $content
    ): array {
        $this->record(static function (PDO $db, string $table) use ($task_id, $creator_id, $title, $content) {
            if ($task_id) {
                $sql = "UPDATE $table SET creator_id=:creator_id, title=:title, content=:content WHERE id=:id";
            } else {
                $sql = "INSERT INTO $table (title, content, creator_id) VALUES (:title, :content, :creator_id)";
            }

            $query = $db->prepare($sql);

            if ($task_id) {
                $query->bindParam('id', $id, PDO::PARAM_INT);
            }

            $query->bindParam('creator_id', $creator_id);
            $query->bindParam('title', $title, PDO::PARAM_STR);
            $query->bindParam('content', $content, PDO::PARAM_STR);

            $query->execute();
        });
    }

    public function changeComplete(int $id, bool $is_complete): void
    {
        $this->record(static function (PDO $db, string $table) use ($id, $is_complete) {
            $sql = "UPDATE $table SET is_complete=:is_complete WHERE id=:id";
            $query = $db->prepare($sql);
            $query->bindParam('is_complete', $is_complete, PDO::PARAM_BOOL);
            $query->bindParam('id', $id, PDO::PARAM_INT);
            $query->execute();
        });
    }
}
