<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'domain_name', 'register', 'ns1', 'ns2',
    ];
    private string $ns1;
    private string $ns2;
    private mixed $register;
    private mixed $name;
    private mixed $domain_name;

    /**
     * Получить имя регистратора домена.
     *
     * @return string
     */
    public function getRegistrarName(): string
    {
        return $this->register;
    }

    /**
     * Установить новую DNS запись.
     *
     * @param string $ns1
     * @param string $ns2
     */
    public function setDnsRecords(string $ns1, string $ns2): void
    {
        $this->ns1 = $ns1;
        $this->ns2 = $ns2;
        $this->save();
    }

    /**
     * Получить полное доменное имя.
     *
     * @return string
     */
    public function getFullDomainName(): string
    {
        return "{$this->name}.{$this->domain_name}";
    }

    /**
     * Проверить, используется ли данный регистратор.
     *
     * @param string $registrar
     * @return bool
     */
    public function isRegistrar(string $registrar): bool
    {
        return $this->register === $registrar;
    }

    /**
     * Проверить, соответствуют ли DNS записи заданным.
     *
     * @param string $ns1
     * @param string $ns2
     * @return bool
     */
    public function isDnsRecordsMatch(string $ns1, string $ns2): bool
    {
        return $this->ns1 === $ns1 && $this->ns2 === $ns2;
    }
}
