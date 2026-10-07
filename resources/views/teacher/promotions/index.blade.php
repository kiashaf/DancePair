@extends('teacher.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Promotions'
        : 'Promotions'
)

@section(
    'page-title',
    app()->getLocale() === 'fr'
        ? 'Promotions'
        : 'Promotions'
)

@section('content')

<div class="container py-4">

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                {{ app()->getLocale() === 'fr'
                    ? 'Mes promotions'
                    : 'My Promotions'
                }}
            </h3>

            <p class="text-muted mb-0">
                {{ app()->getLocale() === 'fr'
                    ? 'Créez et gérez vos offres promotionnelles.'
                    : 'Create and manage your promotional offers.'
                }}
            </p>

        </div>


        <a
            href="{{ route('teacher.promotions.create') }}"
            class="btn btn-primary"
        >
            +
            {{ app()->getLocale() === 'fr'
                ? 'Créer une promotion'
                : 'Create Promotion'
            }}
        </a>

    </div>


    @if($promotions->isEmpty())

        <div class="card p-4 text-center">

            <h5 class="mb-2">
                {{ app()->getLocale() === 'fr'
                    ? 'Aucune promotion'
                    : 'No Promotions Yet'
                }}
            </h5>

            <p class="text-muted mb-0">
                {{ app()->getLocale() === 'fr'
                    ? 'Créez votre première promotion pour attirer davantage d’étudiants.'
                    : 'Create your first promotion to attract more students.'
                }}
            </p>

        </div>

    @else

        <div class="card">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>
                                {{ app()->getLocale() === 'fr'
                                    ? 'Titre'
                                    : 'Title'
                                }}
                            </th>

                            <th>
                                {{ app()->getLocale() === 'fr'
                                    ? 'Type'
                                    : 'Type'
                                }}
                            </th>

                            <th>
                                {{ app()->getLocale() === 'fr'
                                    ? 'Offre'
                                    : 'Offer'
                                }}
                            </th>

                            <th>
                                {{ app()->getLocale() === 'fr'
                                    ? 'Début'
                                    : 'Start'
                                }}
                            </th>

                            <th>
                                {{ app()->getLocale() === 'fr'
                                    ? 'Fin'
                                    : 'End'
                                }}
                            </th>

                            <th>
                                {{ app()->getLocale() === 'fr'
                                    ? 'Statut'
                                    : 'Status'
                                }}
                            </th>

                            <th class="text-end">
                                {{ app()->getLocale() === 'fr'
                                    ? 'Actions'
                                    : 'Actions'
                                }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($promotions as $promotion)

                            @php

                                if (!$promotion->is_active) {

                                    $status = 'disabled';

                                } elseif (
                                    $promotion->starts_at
                                    &&
                                    $promotion->starts_at->isFuture()
                                ) {

                                    $status = 'scheduled';

                                } elseif (
                                    $promotion->ends_at
                                    &&
                                    $promotion->ends_at->isPast()
                                ) {

                                    $status = 'expired';

                                } else {

                                    $status = 'active';

                                }

                            @endphp


                            <tr>

                                {{-- TITLE --}}

                                <td>

                                    <strong>
                                        {{ $promotion->title }}
                                    </strong>

                                </td>


                                {{-- TYPE --}}

                                <td>

                                    @if($promotion->type === 'free_session')

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Séance gratuite'
                                            : 'Free Session'
                                        }}

                                    @elseif($promotion->type === 'package_discount')

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Forfait'
                                            : 'Package'
                                        }}

                                    @elseif($promotion->type === 'discount_code')

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Code promotionnel'
                                            : 'Discount Code'
                                        }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- OFFER --}}

                                <td>

                                    @if($promotion->type === 'free_session')

                                        {{ app()->getLocale() === 'fr'
                                            ? '1 séance gratuite'
                                            : '1 Free Session'
                                        }}


                                    @elseif($promotion->type === 'package_discount')

                                        {{ $promotion->package_size }}

                                        {{ app()->getLocale() === 'fr'
                                            ? 'séances'
                                            : 'Sessions'
                                        }}

                                        @if($promotion->discount_percent)

                                            —
                                            {{ (int) $promotion->discount_percent }}%

                                            {{ app()->getLocale() === 'fr'
                                                ? 'de rabais'
                                                : 'Off'
                                            }}

                                        @endif


                                    @elseif($promotion->type === 'discount_code')

                                        <div>

                                            <strong>
                                                {{ $promotion->code }}
                                            </strong>

                                            @if($promotion->discount_percent)

                                                —
                                                {{ (int) $promotion->discount_percent }}%

                                                {{ app()->getLocale() === 'fr'
                                                    ? 'de rabais'
                                                    : 'Off'
                                                }}

                                            @endif

                                        </div>


                                        @if($promotion->usage_limit)

                                            <small class="text-muted">

                                                {{ app()->getLocale() === 'fr'
                                                    ? 'Limite :'
                                                    : 'Limit:'
                                                }}

                                                {{ $promotion->usage_limit }}

                                                |

                                                {{ app()->getLocale() === 'fr'
                                                    ? 'Utilisé :'
                                                    : 'Used:'
                                                }}

                                                {{ $promotion->used_count ?? 0 }}

                                            </small>

                                        @else

                                            <small class="text-muted">

                                                {{ app()->getLocale() === 'fr'
                                                    ? 'Aucune limite globale'
                                                    : 'No global usage limit'
                                                }}

                                            </small>

                                        @endif


                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- START --}}

                                <td>

                                    {{ $promotion->starts_at
                                        ? $promotion->starts_at->format('Y-m-d')
                                        : '—'
                                    }}

                                </td>


                                {{-- END --}}

                                <td>

                                    {{ $promotion->ends_at
                                        ? $promotion->ends_at->format('Y-m-d')
                                        : '—'
                                    }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($status === 'active')

                                        <span class="badge bg-success">

                                            {{ app()->getLocale() === 'fr'
                                                ? 'Active'
                                                : 'Active'
                                            }}

                                        </span>


                                    @elseif($status === 'scheduled')

                                        <span class="badge bg-info text-dark">

                                            {{ app()->getLocale() === 'fr'
                                                ? 'Planifiée'
                                                : 'Scheduled'
                                            }}

                                        </span>


                                    @elseif($status === 'expired')

                                        <span class="badge bg-secondary">

                                            {{ app()->getLocale() === 'fr'
                                                ? 'Expirée'
                                                : 'Expired'
                                            }}

                                        </span>


                                    @else

                                        <span class="badge bg-danger">

                                            {{ app()->getLocale() === 'fr'
                                                ? 'Désactivée'
                                                : 'Disabled'
                                            }}

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'teacher.promotions.edit',
                                            $promotion
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        {{ app()->getLocale() === 'fr'
                                            ? 'Modifier'
                                            : 'Edit'
                                        }}
                                    </a>


                                    <form
                                        action="{{ route(
                                            'teacher.promotions.destroy',
                                            $promotion
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            '{{ app()->getLocale() === 'fr'
                                                ? 'Supprimer cette promotion ?'
                                                : 'Delete this promotion?'
                                            }}'
                                        );"
                                    >

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            {{ app()->getLocale() === 'fr'
                                                ? 'Supprimer'
                                                : 'Delete'
                                            }}
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>

@endsection