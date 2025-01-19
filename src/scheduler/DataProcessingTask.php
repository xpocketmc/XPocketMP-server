<?php

declare(strict_types=1);

namespace pocketmine\scheduler;

use React\Promise\PromiseInterface;
use React\Promise\Deferred;
use function React\Promise\all;
use function React\Promise\resolve;
use pocketmine\Server;

class DataProcessingTask extends AsyncTask{

    /** @var array<string> */
    private array $data;

    private int $batchSize;

    /**
     * @param array<string> $data
     */
    public function __construct(array $data, int $batchSize = 100){
        $this->data = $data;
        $this->batchSize = max(1, $batchSize);
    }

    /**
     * @return PromiseInterface<array<string>>
     */
    public function onRunAsync(): PromiseInterface{
        $deferred = new Deferred();

        $chunks = array_chunk($this->data, $this->batchSize);
        $promises = array_map(fn(array $chunk): PromiseInterface => resolve($this->processBatch($chunk)), $chunks);

        all($promises)->then(
            function(array $results) use ($deferred): void{
                $deferred->resolve(array_merge(...$results));
            },
            function(\Throwable $e) use ($deferred): void{
                $deferred->reject($e);
            }
        );

        return $deferred->promise();
    }

    /**
     * @param array<string> $batch
     * @return array<string>
     */
    private function processBatch(array $batch): array{
        $processed = [];
        foreach ($batch as $item){
            $processed[] = strtoupper($item);
        }
        return $processed;
    }

    /**
     * @param array<string> $result
     */
    public function onComplete(array $result): void{
        Server::getInstance()->getLogger()->info("Data processing complete. Processed items: " . count($result));
    }

    public function onRun(): void{
    }
}
