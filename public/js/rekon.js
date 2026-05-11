document.addEventListener('DOMContentLoaded', function () {

    const targetProvinsi = 5.4000;

    const inputs = document.querySelectorAll('.nilaiInput');

    inputs.forEach(input => {

        input.addEventListener('input', hitungProvinsi);

    });

    function hitungProvinsi() {

        let total = 0;

        inputs.forEach(input => {

            const newValue =
                parseFloat(input.value) || 0;

            total += newValue;

            const currentValue =
                parseFloat(input.dataset.current);

            const diff =
                newValue - currentValue;

            const diffCell = input
                .closest('tr')
                .querySelector('.selisihCell');

            diffCell.innerText =
                (diff >= 0 ? '+' : '') +
                diff.toFixed(4);

            if (diff === 0) {

                diffCell.className =
                    'selisihCell px-6 py-4 text-center text-slate-500 font-medium';

            } else if (diff > 0) {

                diffCell.className =
                    'selisihCell px-6 py-4 text-center text-green-600 font-semibold';

            } else {

                diffCell.className =
                    'selisihCell px-6 py-4 text-center text-red-600 font-semibold';

            }

        });

        const rata =
            total / inputs.length;

        const hasilProvinsi =
            document.getElementById('hasilProvinsi');

        hasilProvinsi.innerText =
            rata.toFixed(4);

        const statusBox =
            document.getElementById('statusBox');

        const statusText =
            document.getElementById('statusText');

        const selisihProvinsi =
            Math.abs(rata - targetProvinsi);

        if (selisihProvinsi <= 0.0001) {

            statusBox.className =
                'rounded-xl p-4 min-w-[180px] bg-green-100';

            statusText.className =
                'text-xl font-bold text-green-700 mt-1';

            hasilProvinsi.className =
                'text-3xl font-bold text-green-600 mt-1';

            statusText.innerText =
                'Sudah Seimbang';

        } else {

            statusBox.className =
                'rounded-xl p-4 min-w-[180px] bg-red-100';

            statusText.className =
                'text-xl font-bold text-red-700 mt-1';

            hasilProvinsi.className =
                'text-3xl font-bold text-red-600 mt-1';

            statusText.innerText =
                'Belum Seimbang';

        }

    }

    // countdown refresh

    let totalSeconds = 300;

    setInterval(() => {

        totalSeconds--;

        const minutes = String(
            Math.floor(totalSeconds / 60)
        ).padStart(2, '0');

        const seconds = String(
            totalSeconds % 60
        ).padStart(2, '0');

        document.getElementById('countdown').innerText =
            `${minutes}:${seconds}`;

        if (totalSeconds <= 0) {

            totalSeconds = 300;

        }

    }, 1000);

});