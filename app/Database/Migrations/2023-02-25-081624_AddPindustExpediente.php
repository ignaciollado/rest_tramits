<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPindustExpediente extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idExp' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'empresa' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'unique' => true
            ],
            'nif' => [
                'type' => 'VARCHAR',
                'constraint' => 9,
                'null' => false,
                'unique' => false
            ],
            'convocatoria' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => false,
                'unique' => false
            ],
            'tipo_tramite' => [
                'type' => 'VARCHAR',
                'constraint' => 9,
                'null' => false,
                'unique' => false
            ],            
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pindust_expediente');
    }

    public function down()
    {
        $this->forge->dropTable('pindust_expediente');
    }
}
