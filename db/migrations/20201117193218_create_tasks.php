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
    public function change(): void
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
            ->addColumn('content', 'string', ['limit' => 100])
            ->addIndex(['name'], ['unique' => true])
            ->addTimestamps()
            ->create();
    }
}
