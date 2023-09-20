<?php

namespace Webkul\Customer\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TypeDocumentTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('type_document')->delete();

        DB::table('type_document')->insert([
            [
                'id'              => 1,
                'prefijo'         => 'R',
                'name'            => 'Registro Civil',
            ], [
                'id'              => 2,
                'prefijo'         => 'T',
                'name'            => 'Tarjeta de Identidad',
            ], [
                'id'              => 3,
                'prefijo'         => 'C',
                'name'            => 'Cédula de ciudadanía',
            ], [
                'id'              => 4,
                'prefijo'         => 'X',
                'name'            => 'Tarjeta de extranjería',
            ], [
                'id'              => 5,
                'prefijo'         => 'E',
                'name'            => 'Cédula de extranjería',
            ], [
                'id'              => 6,
                'prefijo'         => 'N',
                'name'            => 'NIT',
            ], [
                'id'              => 7,
                'prefijo'         => 'P',
                'name'            => 'Pasaporte',
            ], [
                'id'              => 8,
                'prefijo'         => 'D',
                'name'            => 'Documento de identificación extranjero',
            ], [
                'id'              => 9,
                'prefijo'         => 'O',
                'name'            => 'NIT de otro país',
            ], [
                'id'              => 10,
                'prefijo'         => 'A',
                'name'            => 'NUIP Deberá utilizarse solamente para el aquiriente',
            ]
        ]);
    }
}