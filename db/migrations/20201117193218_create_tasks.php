<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTasks extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $table = $this->table('tasks');

        $table->addColumn('name', 'string')
            ->addColumn('title', 'string')
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('picture_id', 'integer', ['null' => true])
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'delete' => 'restrict',
                    'update' => 'restrict',
                ]
            )
            ->addForeignKey(
                'picture_id',
                'files',
                'id',
                [
                    'delete' => 'restrict',
                    'update' => 'restrict',
                ]
            )
            ->addColumn('content', 'string', ['limit' => 100, 'null' => true])
            ->addIndex(['name'], ['unique' => true])
            ->addTimestamps()
            ->create();

        $tasks = [
            [
                'id' => 1,
                'name' => 'task_1',
                'title' => 'Таск 1',
                'user_id' => 1,
                'picture_id' => null,
            ],
            [
                'id' => 2,
                'name' => 'task_2',
                'title' => 'Таск 2',
                'user_id' => 1,
                'picture_id' => null,
            ],
            [
                'id' => 3,
                'name' => 'task_3',
                'title' => 'Таск 4',
                'user_id' => 1,
                'picture_id' => null,
            ],
            [
                'id' => 4,
                'name' => 'task_4',
                'title' => 'Таск 5',
                'user_id' => 1,
                'picture_id' => null,
            ],
        ];

        $table->insert($tasks)->save();
    }

    public function down(): void
    {
        $this->table('tasks')->drop();
    }
}
