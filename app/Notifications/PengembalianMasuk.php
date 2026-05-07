<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengembalianMasuk extends Notification
{
    use Queueable;

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
        // Ambil data relasi untuk pesan notifikasi
        $guru = optional($this->detail->peminjaman->guru)->nama ?? 'Guru';
        $siswa = optional($this->detail->peminjaman->siswa)->nama ?? 'Siswa';
        $barang = optional($this->detail->barang)->nama_barang ?? 'Barang';

        return [
            'title'   => 'Pengajuan Pengembalian Baru 🔄',
            'message' => 'Guru ' . $guru . ' mengajukan pengembalian barang ' . $barang . ' milik ' . $siswa,
            'url'     => '/sarpras/pengembalian',
            'id_detail' => $this->detail->id_detail,
            'type' => 'pengembalian' // 🔥 WAJIB
        ];
    }
}