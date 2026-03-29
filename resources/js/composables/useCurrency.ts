import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useCurrency() {
    const page = usePage<any>();
    
    const currency = computed(() => page.props.currency || { current: 'KES', rate: 1, symbol: 'Ksh' });

    const formatPrice = (priceInKes: number | string) => {
        const numericPrice = typeof priceInKes === 'string' ? parseFloat(priceInKes) : priceInKes;
        if (isNaN(numericPrice)) return priceInKes;

        const converted = numericPrice * currency.value.rate;
        
        // Use Intl.NumberFormat for clean formatting
        const formatter = new Intl.NumberFormat('en-KE', {
            minimumFractionDigits: currency.value.current === 'KES' ? 0 : 2,
            maximumFractionDigits: 2,
        });

        return `${currency.value.symbol} ${formatter.format(converted)}`;
    };

    return {
        currency,
        formatPrice
    };
}
