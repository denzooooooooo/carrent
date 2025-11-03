{{-- SECTION 6: ZONES DE SIÈGES --}}
<h2 class="text-xl font-semibold text-primary mb-4 border-b pb-2 mt-8">6. Zones de Sièges (VIP, VVIP, VVVIP)</h2>

<div id="seat-zones-container">
    @if($event->exists && $event->seatZones->count() > 0)
        @foreach($event->seatZones as $index => $zone)
            <div class="seat-zone-item bg-gray-50 p-6 rounded-lg border border-gray-200 mb-4" data-index="{{ $index }}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Zone {{ $index + 1 }}</h3>
                    <button type="button" class="remove-zone-btn text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-150">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nom de la zone FR --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                        <input type="text" name="seat_zones[{{ $index }}][zone_name_fr]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                            value="{{ old('seat_zones.' . $index . '.zone_name_fr', $zone->zone_name_fr) }}">
                    </div>
                    {{-- Nom de la zone EN --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                        <input type="text" name="seat_zones[{{ $index }}][zone_name_en]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                            value="{{ old('seat_zones.' . $index . '.zone_name_en', $zone->zone_name_en) }}">
                    </div>
                    {{-- Code de la zone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                        <input type="text" name="seat_zones[{{ $index }}][zone_code]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                            value="{{ old('seat_zones.' . $index . '.zone_code', $zone->zone_code) }}">
                    </div>
                    {{-- Prix par siège --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                        <input type="number" name="seat_zones[{{ $index }}][price]" required min="0" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                            value="{{ old('seat_zones.' . $index . '.price', $zone->price) }}">
                    </div>
                    {{-- Nombre total de sièges --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                        <input type="number" name="seat_zones[{{ $index }}][total_seats]" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                            value="{{ old('seat_zones.' . $index . '.total_seats', $zone->total_seats) }}">
                    </div>
                    {{-- Description FR --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                        <textarea name="seat_zones[{{ $index }}][description_fr]" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.' . $index . '.description_fr', $zone->description_fr) }}</textarea>
                    </div>
                    {{-- Description EN --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                        <textarea name="seat_zones[{{ $index }}][description_en]" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.' . $index . '.description_en', $zone->description_en) }}</textarea>
                    </div>
                </div>

                {{-- Checkbox Actif --}}
                <div class="flex items-center mt-4">
                    <input type="checkbox" name="seat_zones[{{ $index }}][is_active]" id="zone_active_{{ $index }}" value="1"
                        class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary"
                        {{ old('seat_zones.' . $index . '.is_active', $zone->is_active ?? true) ? 'checked' : '' }}>
                    <label for="zone_active_{{ $index }}" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
                </div>
            </div>
        @endforeach
    @else
        {{-- Template pour nouvelle zone --}}
        <div class="seat-zone-item bg-gray-50 p-6 rounded-lg border border-gray-200 mb-4" data-index="0">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-800">Zone 1</h3>
                <button type="button" class="remove-zone-btn text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-150">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nom de la zone FR --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                    <input type="text" name="seat_zones[0][zone_name_fr]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.zone_name_fr') }}">
                </div>
                {{-- Nom de la zone EN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                    <input type="text" name="seat_zones[0][zone_name_en]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.zone_name_en') }}">
                </div>
                {{-- Code de la zone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                    <input type="text" name="seat_zones[0][zone_code]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.zone_code') }}">
                </div>
                {{-- Prix par siège --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                    <input type="number" name="seat_zones[0][price]" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.price') }}">
                </div>
                {{-- Nombre total de sièges --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                    <input type="number" name="seat_zones[0][total_seats]" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"
                        value="{{ old('seat_zones.0.total_seats') }}">
                </div>
                {{-- Description FR --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                    <textarea name="seat_zones[0][description_fr]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.0.description_fr') }}</textarea>
                </div>
                {{-- Description EN --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                    <textarea name="seat_zones[0][description_en]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">{{ old('seat_zones.0.description_en') }}</textarea>
                </div>
            </div>

            {{-- Checkbox Actif --}}
            <div class="flex items-center mt-4">
                <input type="checkbox" name="seat_zones[0][is_active]" id="zone_active_0" value="1"
                    class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary" checked>
                <label for="zone_active_0" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
            </div>
        </div>
    @endif
</div>

{{-- Bouton pour ajouter une nouvelle zone --}}
<div class="mt-4">
    <button type="button" id="add-zone-btn"
        class="py-2 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md flex items-center">
        <i class="fas fa-plus-circle mr-2"></i> Ajouter une Zone de Siège
    </button>
</div>

{{-- Template caché pour cloner --}}
<div id="zone-template" class="hidden">
    <div class="seat-zone-item bg-gray-50 p-6 rounded-lg border border-gray-200 mb-4" data-index="__INDEX__">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-800">Zone __NUM__</h3>
            <button type="button" class="remove-zone-btn text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-150">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nom de la zone FR --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Français)</label>
                <input type="text" name="seat_zones[__INDEX__][zone_name_fr]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
            </div>
            {{-- Nom de la zone EN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la Zone (Anglais)</label>
                <input type="text" name="seat_zones[__INDEX__][zone_name_en]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
            </div>
            {{-- Code de la zone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Code de la Zone</label>
                <input type="text" name="seat_zones[__INDEX__][zone_code]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
            </div>
            {{-- Prix par siège --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix par Siège (FCFA)</label>
                <input type="number" name="seat_zones[__INDEX__][price]" required min="0" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
            </div>
            {{-- Nombre total de sièges --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Total de Sièges</label>
                <input type="number" name="seat_zones[__INDEX__][total_seats]" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
            </div>
            {{-- Description FR --}}
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Français)</label>
                <textarea name="seat_zones[__INDEX__][description_fr]" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"></textarea>
            </div>
            {{-- Description EN --}}
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Anglais)</label>
                <textarea name="seat_zones[__INDEX__][description_en]" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150"></textarea>
            </div>
        </div>

        {{-- Checkbox Actif --}}
        <div class="flex items-center mt-4">
            <input type="checkbox" name="seat_zones[__INDEX__][is_active]" id="zone_active___INDEX__" value="1"
                class="h-5 w-5 text-primary border-gray-300 rounded focus:ring-primary" checked>
            <label for="zone_active___INDEX__" class="ml-2 text-sm font-medium text-gray-700">Zone Active</label>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let zoneIndex = {{ $event->exists ? $event->seatZones->count() : 1 }};

    // Ajouter une nouvelle zone
    document.getElementById('add-zone-btn').addEventListener('click', function() {
        addNewZone();
    });

    // Supprimer une zone
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-zone-btn') || e.target.closest('.remove-zone-btn')) {
            e.target.closest('.seat-zone-item').remove();
            updateZoneNumbers();
        }
    });

    function addNewZone() {
        const template = document.getElementById('zone-template').innerHTML;
        const newZone = template
            .replace(/__INDEX__/g, zoneIndex)
            .replace(/__NUM__/g, zoneIndex + 1);

        document.getElementById('seat-zones-container').insertAdjacentHTML('beforeend', newZone);
        zoneIndex++;
        updateZoneNumbers();
    }

    function updateZoneNumbers() {
        const zones = document.querySelectorAll('.seat-zone-item');
        zones.forEach((zone, index) => {
            zone.querySelector('h3').textContent = `Zone ${index + 1}`;
        });
    }
});
</script>
