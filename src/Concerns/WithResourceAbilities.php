<?php

namespace Devvir\ResourceTools\Concerns;

trait WithResourceAbilities
{
    private array $abilities = [
        'viewAny', 'view',
        'create', 'update',
        'delete', 'restore', 'forceDelete',
    ];

    /**
     * Resolve the resource to an array.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return array
     */
    public function resolve($request = null)
    {
        $user = $request?->user();
        $data = parent::resolve($request);

        foreach ($this->abilities as $ability) {
            // TODO : properly handle policies for guests (right now all is null for them)
            $data['allows'][$ability] = $user?->can($ability, $this->resource);
        }

        return $data;
    }

    /**
     * Define a custom list of abilities to check, instead of the default list.
     */
    public function checkForAbilities(array|string $abilities): self
    {
        $this->abilities = is_array($abilities) ? $abilities : func_get_args();

        return $this;
    }

    /**
     * Disable ability checks for the current Resource.
     */
    public function withoutAbilities(): self
    {
        return $this->checkForAbilities([]);
    }
}
