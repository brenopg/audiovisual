<?php
use Migrations\AbstractMigration;

class AddNumeroPatrimonioToEquipamentos extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('equipamentos');
        $table->addColumn('numeroPatrimonio', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }
}
