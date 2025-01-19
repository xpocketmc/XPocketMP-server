<?php

declare(strict_types=1);

namespace pocketmine\scheduler;

use React\Promise\PromiseInterface;
use function React\Async\async;
use function React\Promise\all;
use pocketmine\Server;

class DataProcessingTask extends AsyncTask{

    private array $data;
    private int $batchSize;

    public function __construct(array $data,int $batchSize=100){
        $this->data=$data;
        $this->batchSize=$batchSize;
    }

    /**
     * Runs the async task asynchronously
     *
     * @return PromiseInterface
     */
    public function onRunAsync():PromiseInterface{
        return async(function(){
            $chunks=array_chunk($this->data,$this->batchSize);
            $results=[];
            foreach($chunks as $chunk){
                $results[]=resolve($this->processBatch($chunk));
            }
            return all($results)->then(function(array $processedResults){
                return array_merge(...$processedResults);
            });
        })();
    }

    /**
     * Processes a single batch of data
     *
     * @param array $batch
     * @return array
     */
    private function processBatch(array $batch):array{
        $processed=[];
        foreach($batch as $item){
            $processed[]=strtoupper($item); // Contoh proses
        }
        return $processed;
    }

    /**
     * This is executed once the async task has been resolved
     *
     * @param mixed $result
     */
    public function onComplete(mixed $result):void{
        Server::getInstance()->getLogger()->info("Data processing complete. Processed items: ".count($result));
    }
}
