<?php

namespace Spatie\ServerMonitor\Models\Concerns;

use Spatie\ServerMonitor\Events\CheckFailed;
use Spatie\ServerMonitor\Events\CheckWarning;
use Spatie\ServerMonitor\Events\CheckSucceeded;
use Spatie\ServerMonitor\Helpers\ConsoleOutput;
use Spatie\ServerMonitor\Models\Enums\CheckStatus;

trait HandlesCheckResult
{
    public function succeeded(string $message = '')
    {
        $this->status = CheckStatus::SUCCESS;
        $this->message = $message;

        $this->save();

        event(new CheckSucceeded($this));
        ConsoleOutput::info($this->host->name.": check `{$this->type}` succeeded");

        return $this;
    }

    public function warn(string $warningMessage = '')
    {
        $this->status = CheckStatus::WARNING;
        $this->message = $warningMessage;

        $this->save();

        event(new CheckWarning($this));

        ConsoleOutput::info($this->host->name.": check `{$this->type}` issued warning");

        return $this;
    }

    public function failed(string $failureReason = '')
    {
        $this->status = CheckStatus::FAILED;
        $this->message = $failureReason;

        $this->save();

        event(new CheckFailed($this));

        ConsoleOutput::error($this->host->name.": check `{$this->type}` failed");

        return $this;
    }
}
