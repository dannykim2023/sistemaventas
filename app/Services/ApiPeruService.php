<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;

class ApiPeruService
{
    private string $base;
    private string $token;
    private int $timeout;

    public function __construct()
    {
        $cfg = config('services.apiperu');

        $this->base    = rtrim($cfg['base_url'] ?? 'https://apiperu.dev/api', '/');
        $this->token   = $cfg['token'] ?? '';
        $this->timeout = (int) ($cfg['timeout'] ?? 8);
    }

    /**
     * Cliente HTTP configurado
     */
    private function http()
    {
        return Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->withToken($this->token)
            ->timeout($this->timeout)
            ->retry(2, 300);
    }

    /**
     * Consultar datos de un DNI
     *
     * @param  string $dni
     * @return array
     * @throws \InvalidArgumentException si el DNI no es válido
     * @throws \RuntimeException si falla la API
     */
    public function dni(string $dni): array
    {
        // Validar formato
        if (!preg_match('/^\d{8}$/', $dni)) {
            throw new \InvalidArgumentException('❌ El DNI debe tener exactamente 8 dígitos.');
        }

        // Cache para evitar consultas repetidas
        return Cache::remember("apiperu:dni:$dni", now()->addDay(), function () use ($dni) {
            try {
                $res = $this->http()->post("{$this->base}/dni", ['dni' => $dni]);

                // Si falla (4xx o 5xx) lanza excepción
                $res->throw();

                $data = $res->json();

                if (!($data['success'] ?? false)) {
                    throw new \RuntimeException('No se encontraron datos para el DNI ingresado.');
                }

                return $data;
            } catch (RequestException $e) {
                $status = $e->response?->status();
                $message = match ($status) {
                    401 => 'Token inválido o expirado. Verifica tu API_KEY.',
                    429 => 'Límite de consultas alcanzado. Intenta más tarde.',
                    default => 'Error al consultar el servicio de DNI (HTTP ' . $status . ').',
                };
                throw new \RuntimeException($message);
            } catch (\Throwable $e) {
                throw new \RuntimeException('Error interno al consultar DNI: ' . $e->getMessage());
            }
        });
    }

    /**
     * Consultar datos de un RUC
     *
     * @param  string $ruc
     * @return array
     * @throws \InvalidArgumentException si el RUC no es válido
     * @throws \RuntimeException si falla la API
     */
    public function ruc(string $ruc): array
    {
        // Validar formato
        if (!preg_match('/^\d{11}$/', $ruc)) {
            throw new \InvalidArgumentException('❌ El RUC debe tener exactamente 11 dígitos.');
        }

        return Cache::remember("apiperu:ruc:$ruc", now()->addDay(), function () use ($ruc) {
            try {
                $res = $this->http()->post("{$this->base}/ruc", ['ruc' => $ruc]);
                $res->throw();

                $data = $res->json();

                if (!($data['success'] ?? false)) {
                    throw new \RuntimeException('No se encontraron datos para el RUC ingresado.');
                }

                return $data;
            } catch (RequestException $e) {
                $status = $e->response?->status();
                $message = match ($status) {
                    401 => 'Token inválido o expirado. Verifica tu API_KEY.',
                    429 => 'Límite de consultas alcanzado. Intenta más tarde.',
                    default => 'Error al consultar el servicio de RUC (HTTP ' . $status . ').',
                };
                throw new \RuntimeException($message);
            } catch (\Throwable $e) {
                throw new \RuntimeException('Error interno al consultar RUC: ' . $e->getMessage());
            }
        });
    }
}
