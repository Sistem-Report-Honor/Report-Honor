<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo('tambah-user');
        $adminRole->givePermissionTo('edit-user');
        $adminRole->givePermissionTo('hapus-user');
        $adminRole->givePermissionTo('lihat-user');
        $adminRole->givePermissionTo('lihat-honor-detail-semua');
        $adminRole->givePermissionTo('lihat-honor-dasar-semua');
        $adminRole->givePermissionTo('tambah-rapat');
        $adminRole->givePermissionTo('edit-rapat');

        $pemimpinRole = Role::findByName('pimpinan');
        $pemimpinRole->givePermissionTo('tambah-rapat');
        $pemimpinRole->givePermissionTo('edit-rapat');
        $pemimpinRole->givePermissionTo('lihat-rapat');
        $pemimpinRole->givePermissionTo('absen');
        $pemimpinRole->givePermissionTo('ganti-password');
        $pemimpinRole->givePermissionTo('lihat-honor-detail-pribadi');
        $pemimpinRole->givePermissionTo('lihat-honor-dasar-pribadi');

        $keuanganRole = Role::findByName('keuangan');
        $keuanganRole->givePermissionTo('lihat-honor-detail-semua');
        $keuanganRole->givePermissionTo('lihat-honor-dasar-semua');
        $keuanganRole->givePermissionTo('print-honor');

        $anggotaRole = Role::findByName('anggota');
        $anggotaRole->givePermissionTo('absen');
        $anggotaRole->givePermissionTo('ganti-password');
        $anggotaRole->givePermissionTo('lihat-honor-detail-pribadi');
        $anggotaRole->givePermissionTo('lihat-honor-dasar-pribadi');
    }
}
