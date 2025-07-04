<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MedicineController;
class CheckMedicines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  //  protected $signature = 'app:check-medicines';

    /**
     * The console command description.
     *
     * @var string
     */
    //protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   /* public function handle()
    {
        //
    }
*/
      protected $signature = 'medicines:check';
    protected $description = 'Check medicines stock and expiration dates';

    public function handle()
    {
        $controller = new MedicineController();
        $controller->checkStockAndExpiration();
        $this->info('Medicamentos verificados com sucesso!');
    }
}
