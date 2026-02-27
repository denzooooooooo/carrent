@extends('admin.layouts.app')

@section('title', 'Importer des Packages')

@section('content')

    <div class="max-w-4xl mx-auto py-8">
        <div class="flex justify-between items-center mb-8 border-b pb-2">
            <h1 class="text-3xl font-bold text-dark gradient-text">Importer des Packages depuis Excel</h1>
            <a href="{{ route('admin.events.index') }}"
                class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-600 hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        {{-- Messages de Session --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{!! session('error') !!}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form action="{{ route('admin.events.import-packages') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">Événement (Optionnel)</label>
                    <select name="event_id" id="event_id" class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Sélectionner un événement (laisser vide pour détection auto) --</option>
                        @foreach(\App\Models\Event::all() as $event)
                            <option value="{{ $event->id }}">{{ $event->title_fr }} ({{ $event->city }})</option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Si laissé vide, l'événement sera détecté automatiquement depuis le fichier CSV</p>
                </div>

                <div class="mb-6">
                    <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">Fichier Excel ou CSV (.xlsx, .xls, .csv) *</label>
                    <input type="file" name="excel_file" id="excel_file"
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                        accept=".xlsx,.xls,.csv,text/csv" required>
                    <p class="text-sm text-gray-500 mt-1">Formats acceptés : .xlsx, .xls, .csv — Taille maximale : 10 MB</p>
                </div>

                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800 mb-2">Format du fichier attendu (1ère ligne = en-têtes) :</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li><strong>event_title</strong> ou <strong>event</strong> : Titre de l'événement</li>
                        <li><strong>city</strong> : Ville de l'événement (alternative si event_title non trouvé)</li>
                        <li><strong>package_name_fr</strong> ou <strong>package_name</strong> : Nom du package <span class="text-red-600">(obligatoire)</span></li>
                        <li><strong>package_name_en</strong> : Nom en anglais</li>
                        <li><strong>package_code</strong> ou <strong>code</strong> : Code du package</li>
                        <li><strong>description_fr</strong> ou <strong>description</strong> : Description</li>
                        <li><strong>included</strong> ou <strong>inclus</strong> : Ce qui est inclus</li>
                        <li><strong>price</strong> ou <strong>prix</strong> : Prix <span class="text-red-600">(obligatoire)</span></li>
                        <li><strong>currency</strong> ou <strong>devise</strong> : Devise (défaut : XOF)</li>
                        <li><strong>available_quantity</strong> ou <strong>quantite</strong> : Quantité disponible</li>
                        <li><strong>max_per_order</strong> ou <strong>max_order</strong> : Maximum par commande</li>
                    </ul>
                    <p class="text-xs text-blue-600 mt-3">💡 Exemple de première ligne : <code>event_title,package_name_fr,price,currency,available_quantity</code></p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                        class="py-2 px-6 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md flex items-center">
                        <i class="fas fa-file-import mr-2"></i> Importer les Packages
                    </button>
                </div>
            </form>
        </div>

    </div>

@endsection

