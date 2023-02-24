<?php
namespace SentryExtra\v3;

use Sentry\SentrySdk;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\Transaction;
use Sentry\Tracing\TransactionContext;

class Parser extends \MODX\Revolution\modParser
{
    /**
     * @var Transaction
     */
    private $transaction = null;
    /**
     * A reference to the Sentry instance
     * @var \Sentry $modx
     */
    public $sentry = null;

    public function startTransaction()
    {
        // Create a transaction context
        $transactionContext = new TransactionContext();
        $transactionContext->setName(
            'modParser('.$this->modx->resourceMethod.' : '.$this->modx->resourceIdentifier.')'
        );
        $transactionContext->setOp('modx.parser');// Start the transaction
        $this->transaction = \Sentry\startTransaction($transactionContext);

        // Set the current transaction as the current span, so we can retrieve it later
        SentrySdk::getCurrentHub()->setSpan($this->transaction);
    }

    public function endTransaction()
    {
        if ($this->transaction === null) {
            return;
        }
        // Set the current span back to the transaction since we just finished the previous span
        SentrySdk::getCurrentHub()->setSpan($this->transaction);

        // Finish the transaction, this submits the transaction, and it's span to Sentry
        $this->transaction->finish();
    }
    /**
     * Processes a modElement tag and returns the result.
     *
     * @param string $tag A full tag string parsed from content.
     * @param boolean $processUncacheable
     * @return mixed The output of the processed element represented by the
     * specified tag.
     */
    public function processTag($tag, $processUncacheable = true)
    {
        $parent = SentrySdk::getCurrentHub()->getSpan();
        $span = null;

        // Check if we have a parent span (this is the case if we started a transaction earlier)
        if ($parent !== null) {
            $context = new SpanContext();
            $context->setOp('processTag');
            $context->setDescription(htmlentities(trim($tag[0]), ENT_QUOTES, 'UTF-8'));
            $span = $parent->startChild($context);

            // Set the current span to the span we just started
            SentrySdk::getCurrentHub()->setSpan($span);
        }
        // Process the tag
        $elementOutput = parent::processTag($tag, $processUncacheable);

        if ($span !== null) {
            $span->finish();

            // Restore the current span back to the parent span
            SentrySdk::getCurrentHub()->setSpan($parent);
        }
        return $elementOutput;
    }
}
