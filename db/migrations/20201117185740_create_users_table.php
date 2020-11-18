<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
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
        $table = $this->table('users');

        $table->addColumn('name', 'string')
            ->addColumn('login', 'string', ['limit' => 50])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('password', 'text')
            ->addIndex(['email', 'login'], ['unique' => true])
            ->create();

        $admin = [
            'id' => 1,
            'name' => 'Админ',
            'login' => 'admin',
            'password' => md5('123'),
            'email' => 'admin@unlimint.com',
        ];

        $table->insert($admin);
        $table->saveData();
    }

    public function down(): void
    {
        $this->table('users')->drop();
    }
}
