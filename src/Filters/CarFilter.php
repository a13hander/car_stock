<?php

namespace Stock\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Models\CarStock\Car;
use function array_filter;
use function array_unique;
use function compact;
use function count;

abstract class CarFilter
{
    public const TYPE_VALUE = 'value';
    public const TYPE_RANGE = 'range';
    public const TYPE_ENUM = 'enum';
    public const TYPE_CLOSURE = 'closure';

    public const ORDER_DEFAULT = 'date-desc';

    public const LIMIT_DEFAULT = 15;
    public const LIMIT_MAX = 96;

    public const EXCLUDES_FIELDS = [
        'order_by', 'offset', 'limit',
    ];

    public const ORDERS = [
        'date-desc',
        'price-asc',
        'price-desc',
        'kilometrage-asc',
    ];

    const LIMITS = [24, 48];

    public const FILTER_ORDERS = [
        [
            'name' => 'По актуальности',
            'value' => 'date-desc'
        ],
        [
            'name' => 'Дешевле',
            'value' => 'price-asc'
        ],
        [
            'name' => 'Дороже',
            'value' => 'price-desc'
        ],
        [
            'name' => 'По пробегу',
            'value' => 'kilometrage-asc'
        ],
    ];

    /** @var Car */
    protected $cars;

    /** @var Collection */
    protected $filters;

    /** @var Collection */
    protected $orders;

    abstract public function setFilters(): void;

    public function __construct(Car $cars)
    {
        $this->cars = $cars;
        $this->filters = new Collection();
        $this->orders = new Collection();

        $this->setFilters();
    }

    /**
     * @param FormRequest $request
     * @return Builder
     */
    public function filtrate(FormRequest $request): Builder
    {
        /** @var Builder $query */
        $query = $this->cars->newQuery();

        $formFields = array_filter(Arr::except($request->validated(), static::EXCLUDES_FIELDS), function ($value) {
            return (is_null($value) || $value === '') ? false : true;
        });

        // Apply filters
        foreach ($this->filters as $filter) {
            $type = $filter['type'];
            $column = $filter['column'];

            if ($type === static::TYPE_VALUE) {
                $value = $formFields[$column] ?? null;
                if ($value !== null) {
                    $this->applyValue($query, $column, $value);
                }
                continue;
            }

            if ($type === static::TYPE_RANGE) {
                $valueFrom = $formFields["{$column}_from"] ?? null;
                $valueTo = $formFields["{$column}_to"] ?? null;
                if ($valueFrom !== null || $valueTo !== null) {
                    $this->applyRange($query, $column, $valueFrom, $valueTo);
                }
                continue;
            }

            if ($type === static::TYPE_ENUM) {
                $values = $formFields[$column] ?? null;
                if ($values !== null) {
                    $enum = (array)$values;
                    if (count($enum)) {
                        $this->applyEnum($query, $column, $filter['enum'], array_unique($values));
                    }
                }
                continue;
            }

            if ($type === static::TYPE_CLOSURE) {
                $closure = $filter['closure'];
                $closure($query, $formFields);
                continue;
            }
        }

        return $query;
    }

    public function orderAndLimit(FormRequest $request, Builder $query)
    {
        // 2. Order
        $selectedOrder = $request->get('order_by') ?? static::ORDER_DEFAULT;
        $orderConfig = $this->orders->firstWhere('key', $selectedOrder);
        $query->orderBy($orderConfig['column'], $orderConfig['order']);
        $query->orderBy('deleted_at');

        // 3. Offset & Limit
        $query
            ->offset($request->get('offset') ?? 0)
            ->limit($request->get('limit') ?? static::LIMIT_DEFAULT);

        return $query;
    }

    public function getMetaData(FormRequest $request, Builder $query): array
    {
        $query = clone $query;
        $filters = Arr::except($request->validated(), static::EXCLUDES_FIELDS);
        $totalEntries = $query->count();
        $totalEntriesInStock = $query->whereNull('deleted_at')->count();

        return [
            'filters' => $filters,
            'order_by' => $request->get('order_by', static::ORDER_DEFAULT),
            'pagination' => [
                'total_entries' => $totalEntries,
                'total_entries_in_stock' => $totalEntriesInStock,
                'offset' => $request->get('offset', 0),
                'limit' => $request->get('limit', static::LIMIT_DEFAULT),
            ],
        ];
    }

    /**
     * Фильтр по значению
     * @param string $column
     * @return CarFilter
     */
    public function setValueFilter(string $column): self
    {
        $this->filters->push([
            'column' => $column,
            'type' => static::TYPE_VALUE
        ]);

        return $this;
    }

    /**
     * Фильтр по диапазону
     * @param string $column
     * @return CarFilter
     */
    public function setRangeFilter(string $column): self
    {
        $this->filters->push([
            'column' => $column,
            'type' => static::TYPE_RANGE,
        ]);

        return $this;
    }

    /**
     * Фильтр по энумерации
     * @param string $column
     * @param array $enum
     * @return CarFilter
     */
    public function setEnumFilter(string $column, array $enum): self
    {
        $this->filters->push([
            'column' => $column,
            'type' => static::TYPE_ENUM,
            'enum' => $enum,
        ]);

        return $this;
    }

    /**
     * Кастомный фильтр
     * @param string $column
     * @param Closure $closure
     * @return CarFilter
     */
    public function setClosureFilter(string $column, Closure $closure): self
    {
        $this->filters->push([
            'column' => $column,
            'type' => static::TYPE_CLOSURE,
            'closure' => $closure,
        ]);

        return $this;
    }

    public function setOrder(string $key, string $column, string $order = 'asc')
    {
        $this->orders->push(compact('key', 'column', 'order'));

        return $this;
    }

    protected function applyValue(Builder $q, string $column, $value): void
    {
        $q->where($column, $value);
    }

    protected function applyRange(Builder $q, string $column, $from = null, $to = null): void
    {
        if ($from !== null && $to === null) {
            $q->where($column, '>=', $from);
            return;
        }

        if ($to !== null && $from === null) {
            $q->where($column, '<=', $to);
            return;
        }

        $q->whereBetween($column, [$from, $to]);
    }

    protected function applyEnum(Builder $q, string $column, array $enum, array $values): void
    {
        $mappedValues = array_map(function ($key) use ($enum) {
            return $enum[$key];
        }, $values);

        $q->whereIn($column, $mappedValues);
    }
}
