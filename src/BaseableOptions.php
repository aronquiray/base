<?php

namespace HalcyonLaravel\Base;

class BaseableOptions
{
    public $storeRules;

    public $storeRuleMessages;

    public $updateRules;

    public $updateRuleMessages;

    /**
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public static function create(): self
    {
        return (new static())->reset();
    }

    /**
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function reset(): self
    {
        $this->storeRules = null;
        $this->storeRuleMessages = [];
        $this->updateRules = null;
        $this->updateRuleMessages = [];

        return $this;
    }

    /**
     * @param array $rules
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function storeRules(array $rules): self
    {
        $this->storeRules = $rules;

        return $this;
    }

    /**
     * @param array $messages
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function storeRuleMessages(array $messages): self
    {
        $this->storeRuleMessages = $messages;

        return $this;
    }

    /**
     * @param array $rules
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function updateRules(array $rules): self
    {
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * @param array $messages
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function updateRuleMessages(array $messages): self
    {
        $this->updateRuleMessages = $messages;

        return $this;
    }
}
