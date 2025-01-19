<?php

declare(strict_types=1);

namespace pocketmine\scheduler;

use React\Promise\PromiseInterface;
use function React\Async\async;
use React\Promise\Promise;
use React\Promise\all;
use pocketmine\Server;

class DataProcessingTask extends AsyncTask{

    /** @var array<string> */
    private array $data;

    private int $batchSize;

    /**
     * @param array<string> $data
     * @param int<1, max> $batchSize
     */
    public function __construct(array $data, int $batchSize = 100){
        $this->data = $data;
        $this->batchSize = $batchSize;
    }

    /**
     * Runs the async task asynchronously
     *
     * @return PromiseInterface<array<string>>
     */
    public function onRunAsync(): PromiseInterface{
        return async(function (): PromiseInterface{
            $chunks = array_chunk($this->data, $this->batchSize);
            $results = [];

            foreach($chunks as $chunk){
                $results[] = $this->processBatch($chunk);
            }

            return all($results)->then(function (array $processedResults): array{
                return array_merge(...$processedResults);
            });
        })();
    }

    /**
     * Processes a single batch of data
     *
     * @param array<string> $batch
     * @return PromiseInterface<array<string>>
     */
    private function processBatch(array $batch): PromiseInterface{
        return async(function () use ($batch): array{
            $processed = [];
            foreach ($batch as $item) {
                $processed[] = strtoupper($item);
            }
            return $processed;
        })();
    }

    /**
     * This is executed once the async task has been resolved
     *
     * @param array<string> $result
     */
    public function onComplete(mixed $result): void{
        if(is_array($result)){
            Server::getInstance()->getLogger()->info("Data processing complete. Processed items: " . count($result));
		}else{
            Server::getInstance()->getLogger()->error("Unexpected result type.");
        }
    }

    /**
     * Placeholder for abstract method from parent
     */
    public function onRun(): void{
        // Method intentionally left empty to fulfill abstract contract
    }
}
