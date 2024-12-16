<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{

    private $godaddyApiKey;
    private $godaddyApiSecret;

    public function __construct() {
        $this->godaddyApiKey = config('app.GODADDY_API_KEY', 'default_key');
        $this->godaddyApiSecret = config('app.GODADDY_API_SECRET', 'default_secret');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $domains = Domain::all();
        return view('domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('domains.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain_name' => 'required|string|max:255|regex:/\./',
            'register' => 'required|string|max:255',
            'ns1' => 'required|string|max:255',
            'ns2' => 'required|string|max:255',
        ]);

        Domain::create($request->all());

        return redirect()->route('domains.index')
            ->with('success', 'Domain created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $domain = Domain::findOrFail($id);
        return view('domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $domain = Domain::findOrFail($id);
        return view('domains.edit', compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain_name' => 'required|string|max:255|regex:/\./',
            'register' => 'required|string|max:255',
            'ns1' => 'required|string|max:255',
            'ns2' => 'required|string|max:255',
        ]);

        $domain = Domain::findOrFail($id);
        $domain->update($request->all());

        if (
            $domain->register === 'GoDaddy' && ($domain->ns1 !== $updatedFields['ns1']
                || $domain->ns2 !== $updatedFields['ns2'])) {
            $this->updateGoDaddyDns($domain->domain_name, $request->ns1, $request->ns2);
        }

        return redirect()->route('domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $domain = Domain::findOrFail($id);
        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Domain deleted successfully.');
    }

    /**
     * @throws GuzzleException
     */
    private function updateGoDaddyDns($domainName, $ns1, $ns2): void
    {
        $client = new Client();
        $response = $client->request(
            'PUT',
            "https://api.godaddy.com/v1/domains/$domainName/records",
            [
                'headers' => [
                    'Authorization' => 'sso-key ' . $this->godaddyApiKey . ':' . $this->godaddyApiSecret,
                    'Content-Type' => 'application/json',],
                'json' => [
                    [
                        'type' => 'NS',
                        'name' => '@',
                        'data' => $ns1,
                        'ttl' => 600
                    ],
                    [
                        'type' => 'NS',
                        'name' => '@',
                        'data' => $ns2,
                        'ttl' => 600
                    ]
                ]
            ]
        );
    }

}
