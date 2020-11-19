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
        string $email,
        ?string $content
    ): int {
        return $this->record(static function (PDO $db, string $table) use (
            $task_id,
            $creator_id,
            $name,
            $title,
            $content,
            $email
        ) {
            if ($task_id) {
                $sql = "UPDATE $table SET  name=:task_name, title=:title, email=:email, content=:content WHERE id=:id";
            } else {
                $sql = "INSERT INTO $table (name, title,email, content, creator_id) VALUES (:task_name, :title, :email, :content, :creator_id)";
            }

            $query = $db->prepare($sql);
            $query->bindValue(':task_name', $name, PDO::PARAM_STR);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->bindValue(':content', $content, PDO::PARAM_STR);

            if ($task_id) {
                $query->bindValue(':id', $task_id, PDO::PARAM_INT);
            } else {
                $query->bindValue(':creator_id', $creator_id, PDO::PARAM_INT);
            }

            $query->execute();

            if (!$task_id) {
                $task_id = $db->lastInsertId();
            }

            return (int) $task_id;
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

    public function associatePicture(int $task_id, int $picture_id): void
    {
        $this->record(static function (PDO $db, string $table) use ($picture_id, $task_id) {
            $sql = "UPDATE $table SET picture_id=:picture_id WHERE id=:id";
            $query = $db->prepare($sql);
            $query->bindParam('picture_id', $picture_id);
            $query->bindParam('id', $task_id);

            $query->execute();
        });
    }
}
