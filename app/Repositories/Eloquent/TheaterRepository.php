<?php

namespace App\Repositories\Eloquent;

use App\Models\Seat;
use App\Models\Theater;
use App\Repositories\Interfaces\TheaterRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TheaterRepository extends BaseRepository implements TheaterRepositoryInterface
{
    /**
     * TheaterRepository constructor.
     * 
     * @param Theater $model
     */
    public function __construct(Theater $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function createWithSeats(array $theaterData): Theater
    {
        $theater = $this->create($theaterData);
        
        // Create seats for the theater
        $rows = range('A', chr(ord('A') + $theaterData['rows'] - 1));
        
        foreach ($rows as $row) {
            for ($number = 1; $number <= $theaterData['seats_per_row']; $number++) {
                // For VIP theaters, every 2 seats can be couple seats
                $type = 'regular';
                if ($theaterData['type'] === 'VIP' && $number % 2 === 0) {
                    $type = 'couple';
                }
                
                Seat::create([
                    'theater_id' => $theater->id,
                    'row' => $row,
                    'number' => $number,
                    'type' => $type,
                ]);
            }
        }
        
        return $theater->fresh(['seats']);
    }
    
    /**
     * @inheritDoc
     */
    public function getSeats(int $theaterId): Collection
    {
        $theater = $this->findById($theaterId);
        return $theater->seats;
    }
}