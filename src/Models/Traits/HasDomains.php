<?php

namespace HalcyonLaravel\Base\Models\Traits;

use HalcyonLaravel\Base\Models\Domain;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

trait HasDomains
{
    /**
     * @return false|string
     */
    public function getDomainsForFieldComponent()
    {
        return json_encode(
            old('domains')
                ? explode(',', old('domains'))
                : ($this->domains ? $this->domains->pluck('machine_name') : [])
        );
    }

    /**
     * @param  bool  $isStringReturn
     * @param  string  $glue
     *
     * @return array|string
     */
    public function getDomainTitles(bool $isStringReturn = true, string $glue = ', ')
    {
        $domains = app('query.cache')->queryCache(function () {
            return $this->domains()->get(['machine_name', 'title']);
        }, [get_class($this), $this->id, 'title', 'machine_name']);

        $domainTitles = [];
        foreach ($domains as $domain) {
            $domainTitles[$domain->machine_name] = $domain->title;
        }

        if ($isStringReturn) {
            if (empty($domainTitles)) {
                return 'No Domains';
            }
            return implode($glue, $domainTitles);
        }

        return $domainTitles;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function domains(): MorphToMany
    {
        return $this->morphToMany(config('base.models.domain', Domain::class), 'domainable');
    }


    public function getDomainUrlAttribute()
    {
        $class = explode('\\', get_class($this));
        $class = $class[count($class) - 1];
        $class = Str::kebab($class);
        $class = Str::plural($class);

        $domain = app('query.cache')->queryCache(function () {
            return $this->domains()->first(['domain']);
        }, [get_class($this), $this->id, 'domain']);

        return add_scheme_host($domain->domain)."/$class/".$this->slug;
    }

    /**
     * @param             $query
     * @param  string|null  $machineName
     *
     * @return mixed
     */
    public function scopeHasCurrentDomain($query, string $machineName = null)
    {
        return $query->whereHas('domains', function ($query) use ($machineName) {
            if (is_null($machineName)) {
                $query->where('domain', current_base_url());
            } else {
                $query->where('machine_name', $machineName);
            }
        });
    }
}