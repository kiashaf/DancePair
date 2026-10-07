@extends('teacher.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Modifier la promotion'
        : 'Edit Promotion'
)

@section(
    'page-title',
    app()->getLocale() === 'fr'
        ? 'Modifier la promotion'
        : 'Edit Promotion'
)

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <a
            href="{{ route('teacher.promotions.index') }}"
            class="btn btn-outline-secondary btn-sm"
        >
            ←
            {{ app()->getLocale() === 'fr'
                ? 'Retour aux promotions'
                : 'Back to Promotions'
            }}
        </a>
    </div>


    <div class="card">

        <div class="card-body">

            <h4 class="mb-4">
                {{ app()->getLocale() === 'fr'
                    ? 'Modifier la promotion'
                    : 'Edit Promotion'
                }}
            </h4>


            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('teacher.promotions.update', $promotion) }}"
            >

                @csrf
                @method('PUT')


                {{-- TITLE --}}

                <div class="mb-3">

                    <label class="form-label">
                        {{ app()->getLocale() === 'fr'
                            ? 'Titre de la promotion'
                            : 'Promotion Title'
                        }}
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title', $promotion->title) }}"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- TYPE --}}

                <div class="mb-3">

                    <label class="form-label">
                        {{ app()->getLocale() === 'fr'
                            ? 'Type de promotion'
                            : 'Promotion Type'
                        }}
                    </label>

                    <select
                        name="type"
                        id="promotion_type"
                        class="form-select"
                        required
                    >

                        <option
                            value="free_session"
                            {{ old('type', $promotion->type) === 'free_session'
                                ? 'selected'
                                : ''
                            }}
                        >
                            {{ app()->getLocale() === 'fr'
                                ? 'Première séance gratuite'
                                : 'First Session Free'
                            }}
                        </option>

                     <!--    <option
                            value="package_discount"
                            {{ old('type', $promotion->type) === 'package_discount'
                                ? 'selected'
                                : ''
                            }}
                        >
                            {{ app()->getLocale() === 'fr'
                                ? 'Rabais sur un forfait'
                                : 'Package Discount'
                            }}
                        </option> -->

                        <option
                            value="discount_code"
                            {{ old('type', $promotion->type) === 'discount_code'
                                ? 'selected'
                                : ''
                            }}
                        >
                            {{ app()->getLocale() === 'fr'
                                ? 'Code promotionnel'
                                : 'Discount Code'
                            }}
                        </option>

                    </select>

                </div>


                {{-- FREE SESSION --}}

                <div
                    id="free_session_section"
                    class="alert alert-info d-none"
                >
                    {{ app()->getLocale() === 'fr'
                        ? 'Cette promotion offre une seule séance gratuite. Un étudiant ne peut utiliser cette offre qu’une seule fois avec cet instructeur.'
                        : 'This promotion gives one free session. A student can use this offer only once with this instructor.'
                    }}
                </div>


                {{-- PACKAGE --}}

                <div
                    id="package_section"
                    class="d-none"
                >

                    <div class="mb-3">

                        <label class="form-label">
                            {{ app()->getLocale() === 'fr'
                                ? 'Nombre de séances'
                                : 'Number of Sessions'
                            }}
                        </label>

                        <select
                            name="package_size"
                            id="package_size"
                            class="form-select"
                        >

                            <option
                                value="5"
                                {{ old('package_size', $promotion->package_size) == 5
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ app()->getLocale() === 'fr'
                                    ? 'Forfait de 5 séances'
                                    : '5 Sessions Package'
                                }}
                            </option>

                            <option
                                value="10"
                                {{ old('package_size', $promotion->package_size) == 10
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ app()->getLocale() === 'fr'
                                    ? 'Forfait de 10 séances'
                                    : '10 Sessions Package'
                                }}
                            </option>

                            <option
                                value="20"
                                {{ old('package_size', $promotion->package_size) == 20
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ app()->getLocale() === 'fr'
                                    ? 'Forfait de 20 séances'
                                    : '20 Sessions Package'
                                }}
                            </option>

                        </select>

                    </div>

                </div>


                {{-- DISCOUNT CODE --}}

                <div
                    id="discount_code_section"
                    class="d-none"
                >

                    <div class="mb-3">

                        <label class="form-label">
                            {{ app()->getLocale() === 'fr'
                                ? 'Code promotionnel'
                                : 'Discount Code'
                            }}
                        </label>

                        <input
                            type="text"
                            name="code"
                            id="discount_code"
                            class="form-control text-uppercase"
                            value="{{ old('code', $promotion->code) }}"
                            maxlength="50"
                            placeholder="WELCOME10"
                        >

                        <small class="text-muted">
                            {{ app()->getLocale() === 'fr'
                                ? 'Exemple : WELCOME10'
                                : 'Example: WELCOME10'
                            }}
                        </small>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            {{ app()->getLocale() === 'fr'
                                ? 'Limite d’utilisation'
                                : 'Usage Limit'
                            }}
                        </label>

                        <input
                            type="number"
                            name="usage_limit"
                            id="usage_limit"
                            class="form-control"
                            value="{{ old('usage_limit', $promotion->usage_limit) }}"
                            min="1"
                            step="1"
                        >

                        <small class="text-muted">
                            {{ app()->getLocale() === 'fr'
                                ? 'Laissez vide pour aucune limite globale.'
                                : 'Leave empty for no global usage limit.'
                            }}
                        </small>

                    </div>

                </div>


                {{-- DISCOUNT --}}

                <div
                    id="discount_percent_section"
                    class="d-none"
                >

                    <div class="mb-3">

                        <label class="form-label">
                            {{ app()->getLocale() === 'fr'
                                ? 'Rabais (%)'
                                : 'Discount (%)'
                            }}
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="discount_percent"
                                id="discount_percent"
                                class="form-control"
                                value="{{ old('discount_percent', $promotion->discount_percent) }}"
                                min="1"
                                max="{{ $maximumPackageDiscountPercent }}"
                                step="1"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                        <small
                            class="text-muted"
                            id="discount_max_help"
                        ></small>

                    </div>

                </div>


                {{-- START DATE --}}

                <div class="mb-3">

                    <label class="form-label">
                        {{ app()->getLocale() === 'fr'
                            ? 'Date de début'
                            : 'Start Date'
                        }}
                    </label>

                    <input
                        type="datetime-local"
                        name="starts_at"
                        class="form-control"
                        value="{{ old(
                            'starts_at',
                            $promotion->starts_at
                                ? $promotion->starts_at->format('Y-m-d\TH:i')
                                : ''
                        ) }}"
                    >

                </div>


                {{-- END DATE --}}

                <div class="mb-3">

                    <label class="form-label">
                        {{ app()->getLocale() === 'fr'
                            ? 'Date de fin'
                            : 'End Date'
                        }}
                    </label>

                    <input
                        type="datetime-local"
                        name="ends_at"
                        class="form-control"
                        value="{{ old(
                            'ends_at',
                            $promotion->ends_at
                                ? $promotion->ends_at->format('Y-m-d\TH:i')
                                : ''
                        ) }}"
                    >

                </div>


                {{-- ACTIVE --}}

                <div class="form-check form-switch mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        id="is_active"
                        {{ old('is_active', $promotion->is_active)
                            ? 'checked'
                            : ''
                        }}
                    >

                    <label
                        class="form-check-label"
                        for="is_active"
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Promotion active'
                            : 'Promotion Active'
                        }}
                    </label>

                </div>


                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Enregistrer les modifications'
                            : 'Save Changes'
                        }}
                    </button>

                    <a
                        href="{{ route('teacher.promotions.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Annuler'
                            : 'Cancel'
                        }}
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const maximumPackageDiscountPercent =
            @json($maximumPackageDiscountPercent);

        const maximumDiscountCodePercent =
            @json($maximumDiscountCodePercent);

        const isFrench =
            @json(app()->getLocale() === 'fr');


        const typeSelect =
            document.getElementById(
                'promotion_type'
            );

        const packageSection =
            document.getElementById(
                'package_section'
            );

        const freeSessionSection =
            document.getElementById(
                'free_session_section'
            );

        const discountCodeSection =
            document.getElementById(
                'discount_code_section'
            );

        const discountPercentSection =
            document.getElementById(
                'discount_percent_section'
            );

        const packageSize =
            document.getElementById(
                'package_size'
            );

        const discountCode =
            document.getElementById(
                'discount_code'
            );

        const usageLimit =
            document.getElementById(
                'usage_limit'
            );

        const discountPercent =
            document.getElementById(
                'discount_percent'
            );

        const discountMaxHelp =
            document.getElementById(
                'discount_max_help'
            );


        function setDiscountLimit(
            maximum
        ) {

            discountPercent.max =
                maximum;

            if (isFrench) {

                discountMaxHelp.textContent =
                    'Le rabais maximum est de '
                    +
                    maximum
                    +
                    ' %.';

            } else {

                discountMaxHelp.textContent =
                    'Maximum discount is '
                    +
                    maximum
                    +
                    '%.';

            }

        }


        function updatePromotionFields() {

            const type =
                typeSelect.value;


            packageSection.classList.add(
                'd-none'
            );

            freeSessionSection.classList.add(
                'd-none'
            );

            discountCodeSection.classList.add(
                'd-none'
            );

            discountPercentSection.classList.add(
                'd-none'
            );


            packageSize.disabled =
                true;

            discountCode.disabled =
                true;

            usageLimit.disabled =
                true;

            discountPercent.disabled =
                true;


            packageSize.required =
                false;

            discountCode.required =
                false;

            discountPercent.required =
                false;


            discountMaxHelp.textContent =
                '';


            if (
                type ===
                'free_session'
            ) {

                freeSessionSection
                    .classList
                    .remove(
                        'd-none'
                    );

            }


            if (
                type ===
                'package_discount'
            ) {

                packageSection
                    .classList
                    .remove(
                        'd-none'
                    );

                discountPercentSection
                    .classList
                    .remove(
                        'd-none'
                    );


                packageSize.disabled =
                    false;

                discountPercent.disabled =
                    false;


                packageSize.required =
                    true;

                discountPercent.required =
                    true;


                setDiscountLimit(
                    maximumPackageDiscountPercent
                );

            }


            if (
                type ===
                'discount_code'
            ) {

                discountCodeSection
                    .classList
                    .remove(
                        'd-none'
                    );

                discountPercentSection
                    .classList
                    .remove(
                        'd-none'
                    );


                discountCode.disabled =
                    false;

                usageLimit.disabled =
                    false;

                discountPercent.disabled =
                    false;


                discountCode.required =
                    true;

                discountPercent.required =
                    true;


                setDiscountLimit(
                    maximumDiscountCodePercent
                );

            }

        }


        typeSelect.addEventListener(
            'change',
            updatePromotionFields
        );


        updatePromotionFields();

    }
);

</script>

@endsection