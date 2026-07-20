<?php

namespace App\Console\Commands;

use App\Variation;
use App\Utils\ProductUtil;
use Illuminate\Console\Command;

class SyncBatchPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:sync-batch-prices {--business_id= : Limit sync to a single business id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product batch and purchase-line sell prices from variation master prices.';

    protected $productUtil;

    public function __construct(ProductUtil $productUtil)
    {
        parent::__construct();
        $this->productUtil = $productUtil;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $business_id = $this->option('business_id');

        $query = Variation::query()->select('variations.*');

        if (! empty($business_id)) {
            $query->join('products as p', 'p.id', '=', 'variations.product_id')
                ->where('p.business_id', $business_id);
        }

        $count = 0;
        $query->orderBy('variations.id')->chunkById(200, function ($variations) use (&$count) {
            foreach ($variations as $variation) {
                $this->productUtil->syncVariationSellPriceToStockRecords($variation);
                $count++;
            }
        }, 'variations.id', 'id');

        $this->info("Synced batch prices for {$count} variation(s).");

        return 0;
    }
}
