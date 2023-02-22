<?php
namespace SentryExtra;

class Parser extends \modParser
{
    /**
     * @var \Sentry\Tracing\Transaction
     */
    private $transaction = null;
    /**
     * A reference to the Sentry instance
     * @var \Sentry $modx
     */
    public $sentry = null;
    /**
     * @param \xPDO $modx A reference to the modX|xPDO instance
     */
    public function __construct(\xPDO &$modx)
    {
        parent::__construct($modx);
        $this->startTransaction();
    }

    private function startTransaction()
    {
        $transactionContext = new \Sentry\Tracing\TransactionContext();
        $transactionContext->setName('modParser');
        $transactionContext->setOp('modx.parser');// Start the transaction
        $this->transaction = \Sentry\startTransaction($transactionContext);

        // Set the current transaction as the current span, so we can retrieve it later
        \Sentry\SentrySdk::getCurrentHub()->setSpan($this->transaction);
    }

    public function endTransaction()
    {
        // Set the current span back to the transaction since we just finished the previous span
        \Sentry\SentrySdk::getCurrentHub()->setSpan($this->transaction);

        // Finish the transaction, this submits the transaction and it's span to Sentry
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
        $parent = \Sentry\SentrySdk::getCurrentHub()->getSpan();
        $span = null;

        // Check if we have a parent span (this is the case if we started a transaction earlier)
        if ($parent !== null) {
            $context = new \Sentry\Tracing\SpanContext();
            $context->setOp('processTag');
            $context->setDescription(htmlentities(trim($tag[0]), ENT_QUOTES, 'UTF-8'));
            $span = $parent->startChild($context);

            // Set the current span to the span we just started
            \Sentry\SentrySdk::getCurrentHub()->setSpan($span);
        }
        // Process the tag
        $elementOutput = parent::processTag($tag, $processUncacheable);

        if ($span !== null) {
            $span->finish();

            // Restore the current span back to the parent span
            \Sentry\SentrySdk::getCurrentHub()->setSpan($parent);
        }
        return $elementOutput;
    }
}
