<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flight extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'flight_number',
        'airline_id',
    ];

    public function airline() 
    {
        return $this->belongsTo(Airline::class);
    }
    
    public function segments()
    {
        return $this->hasMany(FlightSegment::class);
    }

    public function classes()
    {
        return $this->hasMany(FlightClass::class);
    }

    public function seats()
    {
        return $this->hasMany(FlightSeat::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function generateSeats()
    {
        $classes = $this->classes; // Mengambil semua kelas terkait penerbangan ini

        foreach ($classes as $class) {
            $totalSeats = $class->total_seats; // Total kursi untuk kelas ini
            $seatsPerRow = $this->getSeatsPerRow($class->class_type); // Kursi per baris sesuai dengan tipe kelas
            $rows = ceil($totalSeats / $seatsPerRow); // Hitung total baris berdasarkan total kursi

            // Ambil kursi yang sudah ada untuk penerbangan ini dan tipe kelas ini
            $existingSeats = FlightSeat::where('flight_id', $this->id)
                ->where('class_type', $class->class_type)
                ->get();

            $existingRows = $existingSeats->pluck('row')->toArray(); // Ambil baris yang sudah terpakai
            $seatCounter = 1; // Mulai dari kursi pertama

            for ($row = 1; $row <= $rows; $row++) {
                if (!in_array($row, $existingRows)) { // Jika baris belum terpakai
                    for ($column = 1; $column <= $seatsPerRow; $column++) {
                        if ($seatCounter > $totalSeats) { // Jika semua kursi sudah selesai dibuat
                            break;
                        }

                        // Generate kode kursi (misalnya: A1, B2, dll.)
                        $seatCode = $this->generateSeatCode($row, $column);

                        // Buat entri kursi baru di database
                        FlightSeat::create([
                            'flight_id' => $this->id,
                            'name' => $seatCode,
                            'row' => $row,
                            'column' => $column,
                            'is_available' => true, // Default kursi tersedia
                            'class_type' => $class->class_type,
                        ]);

                        $seatCounter++; // Pindah ke kursi berikutnya
                    }
                }
            }

            foreach ($existingSeats as $existingSeat) {
                if ($existingSeat->column > $seatsPerRow || $existingSeat->row > $rows) {
                    $existingSeat->is_available = false; // Tandai kursi sebagai tidak tersedia
                    $existingSeat->save(); // Simpan perubahan ke database
                }
            }
        } 
    }

    protected function getSeatsPerRow($classType)
    {
        switch ($classType) {
            case 'business':
                return 4; // Untuk kelas bisnis, kursi per baris adalah 4
            case 'economy':
                return 6; // Untuk kelas ekonomi, kursi per baris adalah 6
            default:
                return 4; // Default nilai, misalnya jika tipe kelas tidak dikenali
        }
    }

    private function generateSeatCode($row, $column)
    {
        $rowLetter = chr(64 + $row); // Mengonversi angka baris ke huruf alfabet (A, B, C, dst.)
        return $rowLetter . $column; // Menggabungkan huruf baris dengan nomor kolom, misalnya: A1, B2
    }
}
