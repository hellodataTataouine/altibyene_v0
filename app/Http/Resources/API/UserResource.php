<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'         => (int) $this->id,
            'name'       => (string) $this->name,
            'email'      => (string) $this->email,
            'phone'      => (string) $this->phone,
            'age'        => (int) $this->age,
            'image'      => (string) $this->image,
            'job_title'  => (string) $this->job_title,
            'short_bio'  => (string) $this->short_bio,
            'bio'        => (string) $this->bio,
            'gender'     => (string) $this->gender,
            'country_id' => (int) $this->country_id,
            'state'      => (string) $this->state,
            'city'       => (string) $this->city,
            'address'    => (string) $this->address,
            "facebook"   => (string) $this->facebook,
            "twitter"    => (string) $this->twitter,
            "linkedin"   => (string) $this->linkedin,
            "website"    => (string) $this->website,
            "github"     => (string) $this->github,
        ];
    }
}
