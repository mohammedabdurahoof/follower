<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Admin;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Utils\ApplicationUtils;
/**
 * Description of CreatetableService
 *
 * @author Ritobroto
 */
class CreatetableForCustomerServiceService {

    private $connection;

    /*
     * $this->columns @array
     * used to find out the 3rd last column to put a new column during update table
     */
    private $exceptcolumns = [];

    public function __construct() {
        $this->connection = Schema::Connection(env('DB_CONNECTION'));
        $this->exceptcolumns = ApplicationUtils::getExceptColumns();
    }

    /*
     * Creates Table By table_name
     * Creates Table By table_fields
     * Creates foreignKey to cutomer, organization and service that the table data belongs
     * 
     */

    public function createTable(string $table_name, array $fields) {
        $foreign_key = $this->generateForeignKey($table_name);
        return $this->connection->create($table_name, function (Blueprint $table) use ($fields, $foreign_key) {
                    $table->id();
                    $table->unsignedBigInteger('client_id');
                    $table->unsignedBigInteger('customer_id');
                    foreach ($fields as $column_name => $data_type) {
                        if (!in_array($column_name, $this->exceptcolumns)) {
                            $table->$data_type($column_name)->nullable();
                        }
                    }
                    $table->timestamps();

                    $table->foreign('client_id', $foreign_key."_client_id_fk")
                            ->references('id')
                            ->on('clients')
                            ->onDelete('cascade');

                    $table->foreign('customer_id', $foreign_key."_customer_id_fk")
                            ->references('id')
                            ->on('customers')
                            ->onDelete('cascade');
                });
    }

    public function alterTable(string $table_name, array $fields) {
        $availalbeColumns = $this->deleteColumns($table_name, $fields);
        $after_column = $availalbeColumns[(count($availalbeColumns) - 3)];

        return $this->connection->table($table_name, function (Blueprint $table) use ($fields, $availalbeColumns, $after_column) {
                    foreach ($fields as $column_name => $data_type) {
                        if (!in_array($column_name, $this->exceptcolumns) && !in_array($column_name, $availalbeColumns)) {
                            $table->$data_type($column_name)->nullable()->after($after_column);
                        }
                    }
                });
    }

    public function getColumnListing(string $table_name): array {
        return $this->connection->getColumnListing($table_name);
    }

    private function deleteColumns(string $table_name, array $fields): array {
        $availalbeColumns = $this->getColumnListing($table_name);
        $this->connection->table($table_name, function (Blueprint $table) use ($fields, $availalbeColumns, $table_name) {
            foreach ($availalbeColumns as $column_name) {
                if (!in_array($column_name, $this->exceptcolumns) && !array_key_exists($column_name, $fields) && $this->connection->hasColumn($table_name, $column_name)) {
                    $table->dropColumn($column_name);
                }
            }
        });
        return $this->getColumnListing($table_name);
    }
    
    private function generateForeignKey(string $table_name): string {
        $initials = explode('_', $table_name);
        $return = '';
        foreach($initials as $v){
            $return .= trim($v[0]);
        }
        return $return;
    }

}
