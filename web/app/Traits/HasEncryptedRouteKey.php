<?php

namespace App\Traits;

use App\Support\UrlCrypt;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Fasyankes;
use App\Models\TreatmentFacility;
use App\Models\TransferLocation;
use App\Models\RoadmapAction;

trait HasEncryptedRouteKey
{
    /**
     * Mendapatkan nilai kunci rute terenkripsi untuk pembuatan URL.
     */
    public function getRouteKey()
    {
        return UrlCrypt::encode($this->getKey());
    }

    /**
     * Accessor untuk mendapatkan ID terenkripsi pada view/blade: $model->encrypted_id.
     */
    public function getEncryptedIdAttribute(): string
    {
        return UrlCrypt::encode($this->getKey());
    }

    /**
     * Menyelesaikan model dari nilai parameter rute terenkripsi.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $id = UrlCrypt::decodeId($value);

        if ($id === null) {
            $this->handleInvalidRouteKey();
        }

        $model = $this->where($field ?? $this->getRouteKeyName(), $id)->first();

        if (! $model) {
            $this->handleInvalidRouteKey();
        }

        return $model;
    }

    /**
     * Menangani kasus ID tidak valid atau manipulasi URL dengan mengarahkan kembali ke halaman index.
     */
    protected function handleInvalidRouteKey()
    {
        $redirectUrl = match (static::class) {
            Fasyankes::class => route('fasyankes.index'),
            TreatmentFacility::class => route('treatment-facilities.index'),
            TransferLocation::class => route('transfer-locations.index'),
            RoadmapAction::class => route('roadmap.index'),
            default => url('/'),
        };

        if (request()->hasSession()) {
            request()->session()->flash('error', 'Tautan atau ID data tidak valid atau telah kedaluwarsa.');
        }

        throw new HttpResponseException(redirect($redirectUrl));
    }
}
