<?php

namespace HalcyonLaravel\Base;

class BasableOptions
{
    public $storeRules;
    public $storeRuleMessages;
    public $updateRules;
    public $updateRuleMessages;


    public static function create(): self
    {
        (new self)->reset();
        return new static();
    }

    public function reset(): self
    {
        $this->storeRules = null;
        $this->storeRuleMessages = null;
        $this->updateRules = null;
        $this->updateRuleMessages = null;

        return $this;
    }

    public function storeRules(array $rules): self
    {
        $this->storeRules = $rules;
        return $this;
    }
    
    public function storeRuleMessages(array $messages): self
    {
        $this->storeRuleMessages = $messages;
        return $this;
    }
    
    public function updateRules(array $rules): self
    {
        $this->updateRules = $rules;
        return $this;
    }
    
    public function updateRuleMessages(array $messages): self
    {
        $this->updateRuleMessages = $messages;
        return $this;
    }
}