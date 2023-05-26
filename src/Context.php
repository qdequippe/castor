<?php

namespace Castor;

class Context implements \ArrayAccess
{
    public readonly string $currentDirectory;

    /**
     * @param array<(int|string), mixed> $data        The input parameter accepts an array or an Object
     * @param array<string, string>      $environment A list of environment variables to add to the command
     */
    public function __construct(
        public readonly array $data = [],
        public readonly array $environment = [],
        string $currentDirectory = null,
        public readonly bool $tty = false,
        public readonly bool $pty = true,
        public readonly float|null $timeout = 60,
        public readonly bool $quiet = false,
        public readonly bool $allowFailure = false,
        public readonly bool $notify = false,
        public readonly VerbosityLevel $verbosityLevel = VerbosityLevel::NORMAL,
    ) {
        $this->currentDirectory = $currentDirectory ?? PathHelper::getRoot();
    }

    /** @param array<(int|string), mixed> $data */
    public function withData(array $data, bool $keepExisting = true): self
    {
        return new self(
            $keepExisting ? array_merge($this->data, $data) : $data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    /** @param array<string, string> $environment */
    public function withEnvironment(array $environment, bool $keepExisting = true): self
    {
        return new self(
            $this->data,
            $keepExisting ? array_merge($this->environment, $environment) : $environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withPath(string $path): self
    {
        return new self(
            $this->data,
            $this->environment,
            str_starts_with($path, '/') ? $path : PathHelper::realpath($this->currentDirectory . '/' . $path),
            $this->tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withTty(bool $tty = true): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withPty(bool $pty = true): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withTimeout(float|null $timeout): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withQuiet(bool $quiet = true): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $this->timeout,
            $quiet,
            $this->allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withAllowFailure(bool $allowFailure = true): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $allowFailure,
            $this->notify,
            $this->verbosityLevel,
        );
    }

    public function withNotify(bool $notify = true): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $notify,
            $this->verbosityLevel,
        );
    }

    public function withVerbosityLevel(VerbosityLevel $verbosityLevel): self
    {
        return new self(
            $this->data,
            $this->environment,
            $this->currentDirectory,
            $this->tty,
            $this->pty,
            $this->timeout,
            $this->quiet,
            $this->allowFailure,
            $this->notify,
            $verbosityLevel,
        );
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? throw new \RuntimeException(sprintf('The property "%s" does not exist in the current context.', $offset));
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \LogicException('Context is immutable');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \LogicException('Context is immutable');
    }

    public function isQuiet(): bool
    {
        return VerbosityLevel::QUIET === $this->verbosityLevel;
    }

    public function isVerbose(): bool
    {
        return VerbosityLevel::VERBOSE <= $this->verbosityLevel;
    }

    public function isVeryVerbose(): bool
    {
        return VerbosityLevel::VERY_VERBOSE <= $this->verbosityLevel;
    }

    public function isDebug(): bool
    {
        return VerbosityLevel::DEBUG <= $this->verbosityLevel;
    }
}
