<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFiles extends AbstractMigration
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
        $table = $this->table('files');

        $table->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('original_name', 'string')
            ->addColumn('extension', 'string')
            ->addColumn('mimetype', 'string')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'delete' => 'restrict',
                    'update' => 'restrict',
                ]
            )
            ->addIndex(['original_name'], ['unique' => true])
            ->addTimestamps()
            ->create();
    }

    public function down(): void
    {
        $this->table('files')->drop()->save();
    }
}
