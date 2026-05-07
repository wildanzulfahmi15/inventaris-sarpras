<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PengajuanMasuk extends Notification
{
    protected $detail;

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $guru  = optional($this->detail->peminjaman->guru)->nama ?? 'Guru';
        $siswa = optional($this->detail->peminjaman->siswa)->nama ?? 'Siswa';
        $barang = optional($this->detail->barang)->nama_barang ?? 'Barang';

        return [
            'title'   => 'Pengajuan Peminjaman Baru 📥',
            'message' => 'Guru ' . $guru . ' mengajukan ' . $barang . ' untuk ' . $siswa,
            'url'     => '/sarpras/peminjaman',
             'id_detail' => $this->detail->id_detail, // 🔥 WAJIB
             'type' => 'peminjaman'
        ];
    }
}