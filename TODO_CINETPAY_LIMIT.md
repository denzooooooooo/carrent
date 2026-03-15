# Solution Limite CinetPay pour Gros Montants

## ✅ Problème identifié
- **Limite Sandbox CinetPay:** 1 500 000 XOF maximum par transaction
- **Packages CDM2026:** 2 138 570 FCFA → ERROR_AMOUNT_TOO_HIGH (logs confirmés)

## 🔧 Solutions immédiates implémentées
1. **Validation EventController:** `total_price <= 1500000` ou erreur claire
2. **JS event-details:** Quantity max = floor(1.5M / package_price)
3. **PaymentController:** Messages d'erreur explicites sur limite CinetPay

## 🚀 Solutions pour gros montants (2M+)
### Option 1: **Split Payment** (Recommandé)
```
Paiement en 2x ou 3x → Chaque tranche <1.5M
Ex: 2 138 570 FCFA → 2x 1 069 285 FCFA
```
- Ajouter bouton "Payer en plusieurs fois" sur unified-checkout.blade.php
- Créer PaymentSplitController + model PaymentSplit

### Option 2: **Paiement manuel par virement** (Immédiat)
```
Pour >1.5M: Rediriger vers instructions virement bancaire
- Compte: [DETAILS]
- Réf: booking_number
- Admin confirme manuellement
```
- Modifier PaymentController: if(amount > 1.5M) → payment.instructions

### Option 3: **Upgrade CinetPay Production**
```
1. https://dashboard.cinetpay.com → Passer en Production
2. Demander limite 10M+ XOF
3. Mettre à jour .env:
   CINETPAY_BASE_URL=https://api.cinetpay.com/v2
   [Nouvelles clés production]
```

## 📋 Étapes suivantes
```
[ ] 1. Implémenter split payment (2-3 tranches)
[ ] 2. Ajouter virement bancaire pour VIP packages
[ ] 3. User upgrade CinetPay production
[ ] 4. Test end-to-end gros montants
[ ] 5. Déployer
```

## 💰 Exemple Split (CDM2026 Pitchside 2 138 570 FCFA)
| Tranche | Montant    | Canal     |
|---------|------------|-----------|
| 1/2     | 1 069 285  | Mobile    |
| 2/2     | 1 069 285  | Mobile    |
**Total:** 2 138 570 FCFA ✓

**Contact CinetPay:** support@cinetpay.com pour upgrade urgent

