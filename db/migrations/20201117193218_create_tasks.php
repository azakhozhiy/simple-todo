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
            ->addColumn('email', 'string')
            ->addColumn('creator_id', 'integer', ['null' => true])
            ->addColumn('picture_id', 'integer', ['null' => true])
            ->addColumn('is_complete', 'boolean', ['default' => false])
            ->addForeignKey(
                'creator_id',
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
            ->addIndex(['name'], ['unique' => true])
            ->addColumn('content', 'text', ['null' => true])
            ->addColumn('completed_at', 'timestamp', ['null' => true])
            ->addTimestamps()
            ->create();

        $tasks = [
            [
                'title' => 'Таск 1',
                'name' => 'task_1',
                'email' => 'test1@test.com',
                'creator_id' => 1,
                'picture_id' => null,
                'is_complete' => false,
            ],
            [
                'title' => 'Таск 2',
                'name' => 'task_2',
                'email' => 'test2@test.com',
                'creator_id' => 1,
                'picture_id' => null,
                'is_complete' => true,
            ],
            [
                'title' => 'Таск 3',
                'name' => 'task_3',
                'email' => 'test3@test.com',
                'picture_id' => null,
            ],
            [
                'title' => 'Таск 4',
                'name' => 'task_4',
                'email' => 'test4@test.com',
                'creator_id' => null,
                'picture_id' => null,
            ],
            [
                'title' => 'Таск 5',
                'creator_id' => null,
                'email' => 'test5@test.com',
                'name' => 'task_5',
                'picture_id' => null,
                'content' => '<p>Test content</p>',
            ],
        ];

        $table->insert($tasks)->save();
    }

    public function down(): void
    {
        $this->table('tasks')->drop()->save();
    }
}
