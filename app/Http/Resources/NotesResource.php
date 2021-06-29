<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => (string) $this->id,
            'type' => 'Notes',
            'attributes' => [
                'name' => $this->name,
                'note' => $this->note,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'tags' => [
                    $this->tags,
                ],
                'attachments' => [
                    $this->attachments,
                ]
            ]
        ];
    }
}
