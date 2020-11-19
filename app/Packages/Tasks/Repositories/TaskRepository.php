<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Core\Models\BaseModel;
use App\Packages\Core\Paginator;
use App\Packages\Core\Repositories\UserRepository;
use App\Packages\Files\Repositories\FileRepository;
use App\Packages\Tasks\Models\Task;
use Symfony\Component\HttpFoundation\Request;

class TaskRepository extends CoreRepository
{
    public function getModel(): BaseModel
    {
        return new Task();
    }

    public function loadRelations(array $task): array
    {
        if (isset($task['creator_id']) && $task['creator_id']) {
            $task['creator'] = app(UserRepository::class)->getOneById($task['creator_id']);
        }

        $task['picture_url'] = url('?module=files&action=placeholder');

        if (isset($task['picture_id']) && $task['picture_id']) {
            $file = app(FileRepository::class)->getOneById($task['picture_id']);
            $task['picture_url'] = url('?module=files&action=get&file_name='.$file['original_name']);
        }

        return $task;
    }

    public function getList(
        Request $request,
        int $per_page,
        string $base_url,
        array $columns = [],
        array $wheres = [],
        array $order = []
    ): Paginator {
        $paginator = parent::getList($request, $per_page, $base_url, $columns, $wheres, $order);

        $data = $paginator->getData();

        foreach ($data as $key => $task) {
            $data[$key] = $this->loadRelations($task);
        }

        $paginator->setData($data);

        return $paginator;
    }
}
