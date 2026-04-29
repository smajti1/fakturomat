<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KsefToken extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'ksef_token',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'ksef_token',
    ];

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ksef_token' => 'encrypted',
        ];
    }
}
