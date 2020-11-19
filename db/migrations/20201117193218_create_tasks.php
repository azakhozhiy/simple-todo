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

        $table->addColumn('title', 'string')
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
            ->addColumn('content', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('completed_at', 'timestamp', ['null' => true])
            ->addTimestamps()
            ->create();

        $tasks = [
            [
                'id' => 1,
                'title' => 'Таск 1',
                'creator_id' => 1,
                'picture_id' => null,
                'is_complete' => false,
            ],
            [
                'id' => 2,
                'title' => 'Таск 2',
                'creator_id' => 1,
                'picture_id' => null,
                'is_complete' => true,
            ],
            [
                'id' => 3,
                'title' => 'Таск 3',
                'picture_id' => null,
            ],
            [
                'id' => 4,
                'title' => 'Таск 4',
                'creator_id' => null,
                'picture_id' => null,
            ],
            [
                'id' => 5,
                'title' => 'Таск 4',
                'creator_id' => null,
                'picture_id' => null,
                'content' => '<p>Test content</p>'
            ],
        ];

        $table->insert($tasks)->save();

    }

    public function down(): void
    {
        $this->table('tasks')->drop()->save();
    }
}
