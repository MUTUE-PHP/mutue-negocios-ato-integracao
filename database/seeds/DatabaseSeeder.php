<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SequenciaDocumentoSeeder::class,
            SerieDocumentoSeeder::class,
            MotivoAdminSeeder::class,
            FormaPagamentoAdminSeeder::class,
            StatusSenhaSeeder::class,
//            CanalComunicacaoSeeder::class,
            UserAdminSeeder::class,
            TipoUserAdminSeeder::class,
            TipoTaxaAdminSeeder::class,
            TipoRegimeAdminSeeder::class,
            TipoLicencaAdminSeeder::class,
            TipoClienteAdminSeeder::class,
            StatusLicencaAdminSeeder::class,
            StatusGeraisAdminSeeder::class,
            AtivacaoLicencaAdminSeeder::class,
            BancoAdminSeeder::class,
            CoordenadaBancariaAdminSeeder::class,
            EmpresaAdminSeeder::class,
            LicencaAdminSeeder::class
        ]);

        //Empresa
        $this->call([
            UserClienteSeeder::class,
            TipoClienteEmpresaSeeder::class
        ]);
    }
}
