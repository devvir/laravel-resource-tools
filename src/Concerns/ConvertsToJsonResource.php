<?php

namespace Devvir\ResourceTools\Concerns;

use Illuminate\Http\Resources\Json\JsonResource;

trait ConvertsToJsonResource
{
    /**
     * Override in the Model to define a custom FQN for the JsonResource.
     *
     * @var string|null
     */
    protected $jsonResourceClass = null;

    /**
     * Use the JsonResource as the Model's default array/JSON representation.
     *
     * Ignored if @jsonSerialize() or @toArray() are implemented in the Model,
     * or if it cannot find the JsonResource (if so, set @jsonResourceClass).
     */
    public function toArray(): array
    {
        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, limit: 2)[1]['class'] ?? null;

        if (is_a($caller, JsonResource::class, allow_string: true)) {
            return parent::toArray();
        }

        try {
            $resource = $this->toResource($this->jsonResourceClass);
        } catch (\LogicException $_) {} // No JsonResource found

        return $resource?->resolve(request()) ?? parent::toArray();
    }
}
