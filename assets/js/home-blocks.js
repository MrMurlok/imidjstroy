(function (wp) {
    'use strict';

    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var register = wp.blocks.registerBlockType;
    var Inspector = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var useInnerBlocksProps = wp.blockEditor.useInnerBlocksProps;
    var InnerBlocks = wp.blockEditor.InnerBlocks;
    var RichText = wp.blockEditor.RichText;
    var BlockControls = wp.blockEditor.BlockControls;
    var AlignmentToolbar = wp.blockEditor.AlignmentToolbar;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockPropsSave = wp.blockEditor.useBlockProps;
    var ToggleControl = wp.components.ToggleControl;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var Panel = wp.components.PanelBody;
    var Text = wp.components.TextControl;
    var Range = wp.components.RangeControl;
    var Select = wp.components.SelectControl;
    var Button = wp.components.Button;
    var Textarea = wp.components.TextareaControl;

    function shell(title, description, children) {
        return el(
            'div',
            { className: 'imidjstroy-editor-block' },
            el('div', { className: 'imidjstroy-editor-block__badge' }, 'Имидж Строй'),
            el('h3', null, title),
            description ? el('p', null, description) : null,
            children || null
        );
    }

    function reg(name, title, icon, attributes, edit) {
        register(name, {
            apiVersion: 2,
            title: title,
            icon: icon,
            category: 'imidjstroy',
            attributes: attributes,
            supports: { html: false },
            edit: function (props) {
                var blockProps = useBlockProps({
                    className: 'imidjstroy-editor-block-wrap'
                });

                return el(
                    'div',
                    blockProps,
                    edit(props)
                );
            },
            save: function () { return null; }
        });
    }


    function iconNode(kind) {
        if (kind === 'location') {
            return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
                el('path', { d: 'M12 21s-6-4.35-6-10a6 6 0 1 1 12 0c0 5.65-6 10-6 10Z', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }),
                el('circle', { cx: '12', cy: '11', r: '2.5', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' })
            );
        }
        if (kind === 'email') {
            return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
                el('rect', { x: '3', y: '5', width: '18', height: '14', rx: '2', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }),
                el('path', { d: 'M4 7l8 6 8-6', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' })
            );
        }
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72l.35 2.46a2 2 0 0 1-.57 1.71L8.09 10.7a16 16 0 0 0 5.21 5.21l1.81-1.77a2 2 0 0 1 1.71-.57l2.46.35A2 2 0 0 1 22 16.92z', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' })
        );
    }

    function justifyClass(value) {
        return value ? 'is-align-' + value : 'is-align-left';
    }

    register('imidjstroy/button', {
        apiVersion: 2,
        title: 'Кнопка',
        icon: 'button',
        category: 'imidjstroy',
        parent: ['imidjstroy/hero', 'imidjstroy/section', 'imidjstroy/row'],
        attributes: {
            text: { type: 'string', default: 'Кнопка' },
            url: { type: 'string', default: '' },
            styleType: { type: 'string', default: 'primary' },
            align: { type: 'string', default: 'left' }
        },
        supports: { html: false, reusable: false },
        edit: function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var blockProps = useBlockProps({ className: 'imidjstroy-inline-button ' + justifyClass(a.align) + ' is-style-' + a.styleType });
            return el(
                Fragment,
                null,
                el(BlockControls, null, el(AlignmentToolbar, { value: a.align, onChange: function(v){ s({ align: v || 'left' }); } })),
                el(Inspector,
                    null,
                    el(Panel, { title: 'Настройки кнопки', initialOpen: true },
                        el(Text, { label: 'Ссылка', value: a.url, onChange: function(v){ s({ url: v }); } }),
                        el(Select, { label: 'Стиль', value: a.styleType, options: [
                            { label: 'Основная', value: 'primary' },
                            { label: 'Контурная', value: 'outline' }
                        ], onChange: function(v){ s({ styleType: v }); } })
                    )
                ),
                el('div', blockProps,
                    el('div', { className: 'imidjstroy-inline-button__inner' },
                        el(RichText, {
                            tagName: 'span',
                            className: 'imidjstroy-inline-button__link',
                            value: a.text,
                            allowedFormats: [],
                            placeholder: 'Текст кнопки',
                            onChange: function(v){ s({ text: v }); }
                        })
                    ),
                    props.isSelected ? el(
                        'div',
                        { className: 'imidjstroy-inline-button__url-editor' },
                        el(Text, {
                            label: 'Ссылка кнопки',
                            value: a.url,
                            placeholder: '/catalog/ или https://…',
                            onChange: function(v){ s({ url: v }); },
                            help: 'Можно указать внутренний путь, например /contacts/, или полный URL.'
                        })
                    ) : null
                )
            );
        },
        save: function (props) {
            var a = props.attributes;
            var wrapper = useBlockPropsSave({ className: 'imidjstroy-inline-button ' + justifyClass(a.align) + ' is-style-' + a.styleType });
            return el('div', wrapper,
                el('div', { className: 'imidjstroy-inline-button__inner' },
                    el('a', { href: a.url || '#', className: 'imidjstroy-inline-button__link' }, a.text || 'Кнопка')
                )
            );
        }
    });

    register('imidjstroy/contact-item', {
        apiVersion: 2,
        title: 'Контакт',
        icon: 'location',
        category: 'imidjstroy',
        parent: ['imidjstroy/hero', 'imidjstroy/section', 'imidjstroy/row'],
        attributes: {
            text: { type: 'string', default: '+7 (964) 449-22-29' },
            url: { type: 'string', default: '' },
            kind: { type: 'string', default: 'phone' },
            align: { type: 'string', default: 'left' }
        },
        supports: { html: false, reusable: false },
        edit: function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var blockProps = useBlockProps({ className: 'imidjstroy-contact-line ' + justifyClass(a.align) + ' is-kind-' + a.kind });
            return el(
                Fragment,
                null,
                el(BlockControls, null, el(AlignmentToolbar, { value: a.align, onChange: function(v){ s({ align: v || 'left' }); } })),
                el(Inspector,
                    null,
                    el(Panel, { title: 'Настройки контакта', initialOpen: true },
                        el(Select, { label: 'Тип', value: a.kind, options: [
                            { label: 'Телефон', value: 'phone' },
                            { label: 'Адрес', value: 'location' },
                            { label: 'Email', value: 'email' }
                        ], onChange: function(v){ s({ kind: v }); } }),
                        el(Text, { label: 'Ссылка', value: a.url, onChange: function(v){ s({ url: v }); }, help: 'Например: tel:+79644492229, mailto:test@mail.ru или ссылка на карту.' })
                    )
                ),
                el('div', blockProps,
                    el('div', { className: 'imidjstroy-contact-line__inner' },
                        el('span', { className: 'imidjstroy-contact-line__icon' }, iconNode(a.kind)),
                        el(RichText, {
                            tagName: 'span',
                            className: 'imidjstroy-contact-line__text',
                            value: a.text,
                            allowedFormats: [],
                            placeholder: a.kind === 'location' ? 'Адрес' : 'Контакт',
                            onChange: function(v){ s({ text: v }); }
                        })
                    )
                )
            );
        },
        save: function (props) {
            var a = props.attributes;
            var wrapper = useBlockPropsSave({ className: 'imidjstroy-contact-line ' + justifyClass(a.align) + ' is-kind-' + a.kind });
            var textNode = a.url ? el('a', { href: a.url, className: 'imidjstroy-contact-line__text' }, a.text || '') : el('span', { className: 'imidjstroy-contact-line__text' }, a.text || '');
            return el('div', wrapper,
                el('div', { className: 'imidjstroy-contact-line__inner' },
                    el('span', { className: 'imidjstroy-contact-line__icon' }, iconNode(a.kind)),
                    textNode
                )
            );
        }
    });


    register('imidjstroy/row', {
        apiVersion: 2,
        title: 'Строка элементов',
        description: 'Располагает самостоятельные кнопки, контакты, текст и изображения рядом.',
        icon: 'editor-table',
        category: 'imidjstroy',
        parent: ['imidjstroy/hero', 'imidjstroy/section'],
        attributes: {
            alignItems: { type: 'string', default: 'left' },
            gap: { type: 'number', default: 12 },
            wrap: { type: 'boolean', default: true }
        },
        supports: { html: false, reusable: false },
        edit: function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var allowed = [
                'imidjstroy/button', 'imidjstroy/contact-item',
                'core/paragraph', 'core/heading', 'core/image'
            ];
            var blockProps = useBlockProps({
                className: 'imidjstroy-element-row is-justify-' + a.alignItems + (a.wrap ? ' is-wrap' : ' is-nowrap'),
                style: { '--imidjstroy-row-gap': String(a.gap || 0) + 'px' }
            });
            var inner = useInnerBlocksProps(
                { className: 'imidjstroy-element-row__inner' },
                {
                    allowedBlocks: allowed,
                    templateLock: false,
                    orientation: 'horizontal',
                    renderAppender: function () { return el(InnerBlocks.ButtonBlockAppender); }
                }
            );
            return el(
                Fragment,
                null,
                el(BlockControls, null,
                    el(AlignmentToolbar, {
                        value: a.alignItems,
                        onChange: function(v){ s({ alignItems: v || 'left' }); }
                    })
                ),
                el(Inspector, null,
                    el(Panel, { title: 'Строка элементов', initialOpen: true },
                        el(Range, { label: 'Расстояние между элементами', value: a.gap, min: 0, max: 48, onChange: function(v){ s({ gap: v }); } }),
                        el(ToggleControl, { label: 'Переносить на новую строку при нехватке места', checked: a.wrap, onChange: function(v){ s({ wrap: v }); } })
                    )
                ),
                el('div', blockProps, el('div', inner))
            );
        },
        save: function (props) {
            var a = props.attributes;
            var wrapper = useBlockPropsSave({
                className: 'imidjstroy-element-row is-justify-' + a.alignItems + (a.wrap ? ' is-wrap' : ' is-nowrap'),
                style: { '--imidjstroy-row-gap': String(a.gap || 0) + 'px' }
            });
            var inner = useInnerBlocksProps.save({ className: 'imidjstroy-element-row__inner' });
            return el('div', wrapper, el('div', inner));
        }
    });

    register('imidjstroy/hero', {
        apiVersion: 2,
        title: 'Hero',
        icon: 'cover-image',
        category: 'imidjstroy',
        attributes: {
            titleFirst: { type: 'string', default: 'Строительные материалы' },
            titleAccent: { type: 'string', default: 'оптом и в розницу' },
            text: { type: 'string', default: 'Качественные стройматериалы по лучшим ценам во Владивостоке. Доставка и самовывоз.' },
            primaryText: { type: 'string', default: 'Смотреть каталог' },
            primaryUrl: { type: 'string', default: '/shop/' },
            secondaryText: { type: 'string', default: 'Связаться с нами' },
            secondaryUrl: { type: 'string', default: '/contacts/' },
            backgroundUrl: { type: 'string', default: '' }
        },
        supports: {
            html: false,
            anchor: true
        },
        edit: function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var editorData = window.imidjstroyHomeEditor || {};
            var heroBackground = a.backgroundUrl || editorData.heroBackground || '';
            var phone = editorData.phone || '+7 (964) 449-22-29';
            var city = editorData.city || 'Владивосток';

            var allowed = [
                'core/heading', 'core/paragraph',
                'imidjstroy/button', 'imidjstroy/contact-item', 'imidjstroy/row',
                'core/group', 'core/columns', 'core/column', 'core/image',
                'core/list', 'core/list-item', 'core/spacer', 'core/separator'
            ];

            var template = [
                [ 'core/heading', {
                    level: 1,
                    content: a.titleFirst,
                    className: 'home-hero__title-first'
                } ],
                [ 'core/heading', {
                    level: 2,
                    content: a.titleAccent,
                    className: 'home-hero__title-accent'
                } ],
                [ 'core/paragraph', {
                    content: a.text,
                    className: 'home-hero__text'
                } ],
                [ 'imidjstroy/button', {
                    text: a.primaryText,
                    url: a.primaryUrl,
                    styleType: 'primary',
                    align: 'left'
                } ],
                [ 'imidjstroy/button', {
                    text: a.secondaryText,
                    url: a.secondaryUrl,
                    styleType: 'outline',
                    align: 'left'
                } ],
                [ 'imidjstroy/contact-item', {
                    text: phone,
                    url: 'tel:' + String(phone).replace(/[^\d+]/g, ''),
                    kind: 'phone',
                    align: 'left'
                } ],
                [ 'imidjstroy/contact-item', {
                    text: city,
                    url: '',
                    kind: 'location',
                    align: 'left'
                } ]
            ];

            var blockProps = useBlockProps({
                className: 'home-hero imidjstroy-hero-editor'
            });

            var innerBlocksProps = useInnerBlocksProps(
                { className: 'home-hero__content' },
                {
                    allowedBlocks: allowed,
                    template: template,
                    templateLock: false,
                    renderAppender: function () {
                        return el(InnerBlocks.ButtonBlockAppender);
                    }
                }
            );

            var inspector = el(
                Inspector,
                null,
                el(
                    Panel,
                    { title: 'Фон Hero', initialOpen: true },
                    el(
                        MediaUploadCheck,
                        null,
                        el(MediaUpload, {
                            allowedTypes: ['image'],
                            onSelect: function (media) {
                                s({ backgroundUrl: media && media.url ? media.url : '' });
                            },
                            render: function (obj) {
                                return el(
                                    Fragment,
                                    null,
                                    el(
                                        Button,
                                        { variant: 'secondary', onClick: obj.open },
                                        a.backgroundUrl ? 'Изменить фон' : 'Выбрать свой фон'
                                    ),
                                    a.backgroundUrl
                                        ? el(
                                            Button,
                                            {
                                                variant: 'tertiary',
                                                isDestructive: true,
                                                onClick: function () { s({ backgroundUrl: '' }); }
                                            },
                                            'Вернуть фон темы'
                                        )
                                        : null
                                );
                            }
                        })
                    ),
                    el(
                        'p',
                        { className: 'components-base-control__help' },
                        'Тексты, кнопки и контакты теперь можно добавлять как отдельные элементы. Через «+» вставляй новые кнопки и контакты независимо друг от друга.'
                    )
                )
            );

            return el(
                Fragment,
                null,
                inspector,
                el(
                    'section',
                    blockProps,
                    el(
                        'div',
                        {
                            className: 'home-hero__background',
                            style: heroBackground ? { backgroundImage: 'url("' + heroBackground.replace(/"/g, '%22') + '")' } : {}
                        }
                    ),
                    el('div', { className: 'home-hero__overlay' }),
                    el(
                        'div',
                        { className: 'home-hero__container' },
                        el('div', innerBlocksProps)
                    )
                )
            );
        },
        save: function () {
            return el(InnerBlocks.Content);
        }
    });

    var featureHours = (window.imidjstroyHomeEditor && window.imidjstroyHomeEditor.featureHours)
        ? window.imidjstroyHomeEditor.featureHours
        : 'Пн–Пт: 9:00–18:00';

    var featureDefaults = [
        { title: 'Быстрая доставка', description: 'Доставка по Владивостоку и области' },
        { title: 'Гарантия качества', description: 'Только сертифицированные товары' },
        { title: 'Удобное время', description: featureHours },
        { title: 'Поддержка', description: 'Консультации по выбору материалов' }
    ];

    function normalizeFeatureItems(items) {
        return featureDefaults.map(function (fallback, index) {
            var item = Array.isArray(items) && items[index] ? items[index] : {};
            return {
                title: typeof item.title === 'string' ? item.title : fallback.title,
                description: typeof item.description === 'string' ? item.description : fallback.description
            };
        });
    }

    function featureIconNode(index) {
        var common = { viewBox: '0 0 24 24', 'aria-hidden': 'true' };

        if (index === 0) {
            return el('svg', common,
                el('path', { d: 'M10 17h4V5H2v12h3' }),
                el('path', { d: 'M14 9h4l4 4v4h-3' }),
                el('circle', { cx: '7.5', cy: '17.5', r: '2.5' }),
                el('circle', { cx: '16.5', cy: '17.5', r: '2.5' })
            );
        }

        if (index === 1) {
            return el('svg', common,
                el('path', { d: 'M20 13c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V5l8-3 8 3v8Z' }),
                el('path', { d: 'm9 12 2 2 4-4' })
            );
        }

        if (index === 2) {
            return el('svg', common,
                el('circle', { cx: '12', cy: '12', r: '9' }),
                el('path', { d: 'M12 7v5l3 2' })
            );
        }

        return el('svg', common,
            el('path', { d: 'M4 13a8 8 0 0 1 16 0' }),
            el('path', { d: 'M18 19h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-1v7Z' }),
            el('path', { d: 'M6 19H5a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1v7Z' })
        );
    }

    reg(
        'imidjstroy/features',
        'Преимущества',
        'awards',
        {
            items: {
                type: 'array',
                default: featureDefaults
            }
        },
        function (props) {
            var s = props.setAttributes;
            var items = normalizeFeatureItems(props.attributes.items);

            function updateItem(index, key, value) {
                var nextItems = items.map(function (item) { return Object.assign({}, item); });
                nextItems[index][key] = value;
                s({ items: nextItems });
            }

            return el(
                'section',
                { className: 'home-features imidjstroy-features-editor' },
                el(
                    'div',
                    { className: 'container' },
                    el(
                        'div',
                        { className: 'home-features__grid' },
                        items.map(function (item, index) {
                            return el(
                                'article',
                                { className: 'home-feature-card', key: index },
                                el('div', { className: 'home-feature-card__icon', 'aria-hidden': 'true' }, featureIconNode(index)),
                                el(
                                    'div',
                                    { className: 'home-feature-card__content' },
                                    el(RichText, {
                                        tagName: 'h3',
                                        className: 'home-feature-card__title',
                                        value: item.title,
                                        allowedFormats: [],
                                        disableLineBreaks: true,
                                        placeholder: 'Заголовок',
                                        onChange: function (v) { updateItem(index, 'title', v); }
                                    }),
                                    el(RichText, {
                                        tagName: 'p',
                                        className: 'home-feature-card__description',
                                        value: item.description,
                                        allowedFormats: [],
                                        disableLineBreaks: true,
                                        placeholder: 'Описание',
                                        onChange: function (v) { updateItem(index, 'description', v); }
                                    })
                                )
                            );
                        })
                    )
                )
            );
        }
    );

    var editorCategories = (window.imidjstroyHomeEditor && Array.isArray(window.imidjstroyHomeEditor.categories))
        ? window.imidjstroyHomeEditor.categories
        : [];

    function categoryCountText(count) {
        var value = Math.max(0, parseInt(count, 10) || 0);
        var mod10 = value % 10;
        var mod100 = value % 100;
        var word = 'товаров';

        if (mod10 === 1 && mod100 !== 11) {
            word = 'товар';
        } else if (mod10 >= 2 && mod10 <= 4 && (mod100 < 12 || mod100 > 14)) {
            word = 'товара';
        }

        return value + ' ' + word;
    }

    function categoriesArrowNode(direction) {
        var path = direction === 'prev' ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6';
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' }, el('path', { d: path }));
    }

    function categoriesAllArrowNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M5 12h14' }),
            el('path', { d: 'm13 6 6 6-6 6' })
        );
    }

    reg(
        'imidjstroy/categories',
        'Категории',
        'category',
        {
            title: { type: 'string', default: 'Категории' },
            link_text: { type: 'string', default: 'Смотреть все' }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;

            return el(
                'section',
                { className: 'home-categories imidjstroy-categories-editor' },
                el(
                    'div',
                    { className: 'container' },
                    el(
                        'div',
                        { className: 'home-categories__heading' },
                        el(RichText, {
                            tagName: 'h2',
                            className: 'home-categories__title',
                            value: a.title,
                            allowedFormats: [],
                            disableLineBreaks: true,
                            placeholder: 'Категории',
                            onChange: function (v) { s({ title: v }); }
                        }),
                        el(
                            'div',
                            { className: 'home-categories__all imidjstroy-categories-editor__all' },
                            el(RichText, {
                                tagName: 'span',
                                value: a.link_text,
                                allowedFormats: [],
                                disableLineBreaks: true,
                                placeholder: 'Смотреть все',
                                onChange: function (v) { s({ link_text: v }); }
                            }),
                            categoriesAllArrowNode()
                        )
                    ),
                    editorCategories.length
                        ? el(
                            'div',
                            { className: 'home-categories__carousel-wrap' },
                            el('button', {
                                type: 'button',
                                className: 'home-categories__arrow home-categories__arrow--prev',
                                disabled: true,
                                tabIndex: -1,
                                'aria-hidden': 'true'
                            }, categoriesArrowNode('prev')),
                            el(
                                'div',
                                { className: 'home-categories__viewport' },
                                el(
                                    'div',
                                    { className: 'home-categories__track' },
                                    editorCategories.map(function (category, index) {
                                        var imageStyle = category.image
                                            ? { backgroundImage: 'url("' + String(category.image).replace(/"/g, '%22') + '")' }
                                            : {};

                                        return el(
                                            'div',
                                            { className: 'home-categories__item', key: category.id || index },
                                            el(
                                                'div',
                                                { className: 'category-card' },
                                                category.image
                                                    ? el('div', { className: 'category-card__image', style: imageStyle, 'aria-hidden': 'true' })
                                                    : el('div', { className: 'category-card__fallback', 'aria-hidden': 'true' }, '📦'),
                                                el('div', { className: 'category-card__gradient', 'aria-hidden': 'true' }),
                                                el(
                                                    'div',
                                                    { className: 'category-card__content' },
                                                    el('span', { className: 'category-card__name' }, category.name || 'Без названия'),
                                                    el('span', { className: 'category-card__count' }, categoryCountText(category.count))
                                                )
                                            )
                                        );
                                    })
                                )
                            ),
                            el('button', {
                                type: 'button',
                                className: 'home-categories__arrow home-categories__arrow--next',
                                disabled: true,
                                tabIndex: -1,
                                'aria-hidden': 'true'
                            }, categoriesArrowNode('next'))
                        )
                        : el('div', { className: 'home-categories__empty' }, 'Категории WooCommerce пока не добавлены.')
                )
            );
        }
    );

    var editorProducts = (window.imidjstroyHomeEditor && window.imidjstroyHomeEditor.products)
        ? window.imidjstroyHomeEditor.products
        : { building: [], ad: [], popular: [] };

    function productSectionConfig(type) {
        if (type === 'building') {
            return {
                sectionClass: 'home-building',
                headingClass: 'home-building__heading',
                eyebrowClass: 'home-building__eyebrow',
                titleClass: 'home-building__title',
                allClass: 'home-building__all',
                gridClass: 'home-building__grid',
                defaultTitle: 'Стройматериалы',
                defaultEyebrow: 'Всё для ремонта и строительства'
            };
        }

        if (type === 'ad') {
            return {
                sectionClass: 'home-ad-materials',
                headingClass: 'home-ad-materials__heading',
                eyebrowClass: 'home-ad-materials__eyebrow',
                titleClass: 'home-ad-materials__title',
                allClass: 'home-ad-materials__all',
                gridClass: 'home-ad-materials__grid',
                defaultTitle: 'Рекламные материалы',
                defaultEyebrow: 'Для производства рекламы'
            };
        }

        return {
            sectionClass: 'home-popular',
            headingClass: 'home-popular__heading',
            eyebrowClass: '',
            titleClass: 'home-popular__title',
            allClass: 'home-popular__all',
            gridClass: 'home-popular__grid',
            defaultTitle: 'Популярные товары',
            defaultEyebrow: ''
        };
    }

    function productSectionArrowNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M5 12h14' }),
            el('path', { d: 'm13 6 6 6-6 6' })
        );
    }

    function productViewIconNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z' }),
            el('circle', { cx: '12', cy: '12', r: '3' })
        );
    }

    function productCartIconNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('circle', { cx: '9', cy: '20', r: '1' }),
            el('circle', { cx: '19', cy: '20', r: '1' }),
            el('path', { d: 'M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6' })
        );
    }

    function productPreviewCard(product, index) {
        var available = product && product.available !== false;
        var hasBadge = product && product.badge;
        var badgeClass = 'product-card__badge popular-product-card__badge';
        if (hasBadge && String(product.badge).toLowerCase() === 'скидка') {
            badgeClass += ' is-discount';
        }

        return el(
            'article',
            { className: 'product-card popular-product-card imidjstroy-product-preview-card', key: (product && product.id) || index },
            el(
                'div',
                { className: 'product-card__media popular-product-card__media' },
                hasBadge ? el('span', { className: badgeClass }, product.badge) : null,
                !available ? el('span', { className: 'product-card__availability popular-product-card__availability' }, 'Нет в наличии') : null,
                product && product.image
                    ? el('img', { src: product.image, alt: product.name || '', loading: 'lazy' })
                    : el('div', { className: 'product-card__placeholder popular-product-card__placeholder' }, 'Фото товара')
            ),
            el(
                'div',
                { className: 'product-card__body popular-product-card__body' },
                product && product.category ? el('span', { className: 'product-card__category popular-product-card__category' }, product.category) : null,
                el('h3', { className: 'product-card__name popular-product-card__name' }, (product && product.name) || 'Товар'),
                product && product.description ? el('p', { className: 'product-card__description popular-product-card__description' }, product.description) : null,
                product && product.sku ? el('span', { className: 'product-card__sku popular-product-card__sku' }, 'Артикул: ' + product.sku) : null,
                product && product.stock ? el('span', {
                    className: 'product-card__stock popular-product-card__stock ' + (available ? 'is-in-stock' : 'is-out-of-stock')
                }, product.stock) : null,
                el(
                    'div',
                    { className: 'product-card__footer popular-product-card__footer' },
                    el(
                        'div',
                        { className: 'product-card__price popular-product-card__price' },
                        el('span', { className: 'product-card__amount popular-product-card__amount' }, (product && product.price) || '—'),
                        el('span', { className: 'product-card__unit popular-product-card__unit' }, (product && product.unit) || 'шт.')
                    ),
                    el(
                        'div',
                        { className: 'product-card__actions popular-product-card__actions' },
                        el('span', { className: 'product-card__action popular-product-card__action product-card__action--view popular-product-card__action--view' }, productViewIconNode()),
                        el('span', {
                            className: 'product-card__action popular-product-card__action product-card__action--cart popular-product-card__action--cart' + (available ? '' : ' is-disabled')
                        }, productCartIconNode())
                    )
                )
            )
        );
    }

    reg(
        'imidjstroy/product-section',
        'Товарная секция',
        'products',
        {
            sectionType: { type: 'string', default: 'popular' },
            title: { type: 'string', default: 'Популярные товары' },
            eyebrow: { type: 'string', default: '' },
            link_text: { type: 'string', default: 'Смотреть все' },
            count: { type: 'number', default: 8 }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var config = productSectionConfig(a.sectionType);
            var products = Array.isArray(editorProducts[a.sectionType]) ? editorProducts[a.sectionType] : [];
            var eyebrowValue = typeof a.eyebrow === 'string' && a.eyebrow.trim() !== ''
                ? a.eyebrow
                : config.defaultEyebrow;

            var headingText = a.sectionType === 'popular'
                ? el(RichText, {
                    tagName: 'h2',
                    className: config.titleClass,
                    value: a.title || config.defaultTitle,
                    allowedFormats: [],
                    disableLineBreaks: true,
                    placeholder: config.defaultTitle,
                    onChange: function (v) { s({ title: v }); }
                })
                : el(
                    'div',
                    null,
                    el(RichText, {
                        tagName: 'span',
                        className: config.eyebrowClass,
                        value: eyebrowValue,
                        allowedFormats: [],
                        disableLineBreaks: true,
                        placeholder: config.defaultEyebrow,
                        onChange: function (v) { s({ eyebrow: v }); }
                    }),
                    el(RichText, {
                        tagName: 'h2',
                        className: config.titleClass,
                        value: a.title || config.defaultTitle,
                        allowedFormats: [],
                        disableLineBreaks: true,
                        placeholder: config.defaultTitle,
                        onChange: function (v) { s({ title: v }); }
                    })
                );

            return el(
                'section',
                { className: config.sectionClass + ' home-product-section imidjstroy-product-section-editor imidjstroy-product-section-editor--' + a.sectionType },
                el(
                    'div',
                    { className: 'container' },
                    el(
                        'div',
                        { className: config.headingClass },
                        headingText,
                        el(
                            'div',
                            { className: config.allClass + ' imidjstroy-product-section-editor__all' },
                            el(RichText, {
                                tagName: 'span',
                                value: a.link_text,
                                allowedFormats: [],
                                disableLineBreaks: true,
                                placeholder: 'Смотреть все',
                                onChange: function (v) { s({ link_text: v }); }
                            }),
                            productSectionArrowNode()
                        )
                    ),
                    products.length
                        ? el(
                            'div',
                            { className: config.gridClass + ' imidjstroy-product-section-editor__grid' },
                            products.map(productPreviewCard)
                        )
                        : el('div', { className: 'imidjstroy-product-section-editor__empty' }, 'Для этой секции пока нет товаров WooCommerce.')
                )
            );
        }
    );

    var editorNews = (window.imidjstroyHomeEditor && Array.isArray(window.imidjstroyHomeEditor.news))
        ? window.imidjstroyHomeEditor.news
        : [];

    function newsArrowNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M5 12h14' }),
            el('path', { d: 'm13 6 6 6-6 6' })
        );
    }

    function newsCalendarNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('rect', { x: '3', y: '5', width: '18', height: '16', rx: '2' }),
            el('path', { d: 'M16 3v4M8 3v4M3 10h18' })
        );
    }

    function newsPlaceholderNode() {
        return el(
            'span',
            { className: 'blog-card__placeholder', 'aria-hidden': 'true' },
            el('svg', { viewBox: '0 0 24 24' },
                el('path', { d: 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20' }),
                el('path', { d: 'M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z' })
            )
        );
    }

    function newsPreviewCard(post, index) {
        return el(
            'article',
            { className: 'blog-card imidjstroy-news-preview-card', key: post.id || index },
            el(
                'div',
                { className: 'blog-card__link' },
                el(
                    'div',
                    { className: 'blog-card__media' },
                    post.image
                        ? el('img', { src: post.image, alt: '', 'aria-hidden': 'true' })
                        : newsPlaceholderNode()
                ),
                el(
                    'div',
                    { className: 'blog-card__body' },
                    el('div', { className: 'blog-card__date' }, newsCalendarNode(), el('span', null, post.date || '')),
                    el('h2', null, post.title || 'Без названия'),
                    el('p', null, post.excerpt || ''),
                    el('span', { className: 'blog-card__more' }, 'Читать далее ', newsArrowNode())
                )
            )
        );
    }

    reg(
        'imidjstroy/news',
        'Новости',
        'admin-post',
        {
            title: { type: 'string', default: 'Новости' },
            count: { type: 'number', default: 3 },
            linkText: { type: 'string', default: 'Все новости' }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;

            return el(
                'section',
                { className: 'home-news imidjstroy-news-editor' },
                el(
                    'div',
                    { className: 'container' },
                    el(
                        'div',
                        { className: 'home-news__heading' },
                        el(RichText, {
                            tagName: 'h2',
                            value: a.title,
                            allowedFormats: [],
                            disableLineBreaks: true,
                            placeholder: 'Новости',
                            onChange: function (v) { s({ title: v }); }
                        }),
                        el(
                            'div',
                            { className: 'imidjstroy-news-editor__all' },
                            el(RichText, {
                                tagName: 'span',
                                value: a.linkText,
                                allowedFormats: [],
                                disableLineBreaks: true,
                                placeholder: 'Все новости',
                                onChange: function (v) { s({ linkText: v }); }
                            }),
                            newsArrowNode()
                        )
                    ),
                    editorNews.length
                        ? el('div', { className: 'blog-grid home-news__grid imidjstroy-news-editor__grid' }, editorNews.slice(0, 3).map(newsPreviewCard))
                        : el('div', { className: 'imidjstroy-news-editor__empty' }, 'Опубликованных записей пока нет.')
                )
            );
        }
    );

    reg(
        'imidjstroy/gallery',
        'Галерея',
        'format-gallery',
        {
            title: { type: 'string', default: 'Галерея' },
            imageIds: { type: 'array', default: [] },
            columns: { type: 'number', default: 4 }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;

            var mediaButton = el(
                MediaUploadCheck,
                null,
                el(MediaUpload, {
                    multiple: true,
                    gallery: true,
                    allowedTypes: ['image'],
                    value: a.imageIds,
                    onSelect: function (items) {
                        s({ imageIds: items.map(function (item) { return item.id; }) });
                    },
                    render: function (obj) {
                        return el(
                            Button,
                            { variant: 'primary', onClick: obj.open },
                            a.imageIds.length ? 'Изменить изображения (' + a.imageIds.length + ')' : 'Выбрать изображения'
                        );
                    }
                })
            );

            return el(
                Fragment,
                null,
                el(Inspector, null, el(Panel, { title: 'Галерея', initialOpen: true }, el(Range, { label: 'Колонки', value: a.columns, min: 2, max: 4, onChange: function (v) { s({ columns: v }); } }))),
                shell(
                    'Галерея',
                    'Выбери изображения из медиатеки. Блок можно добавить в любое место главной.',
                    el(
                        'div',
                        { className: 'imidjstroy-editor-fields' },
                        el(Text, { label: 'Заголовок', value: a.title, onChange: function (v) { s({ title: v }); } }),
                        mediaButton
                    )
                )
            );
        }
    );

    function contactPhoneIconNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c1 .3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z' })
        );
    }

    function contactLocationIconNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z' }),
            el('circle', { cx: '12', cy: '10', r: '2.5' })
        );
    }

    function contactSendIconNode() {
        return el('svg', { viewBox: '0 0 24 24', 'aria-hidden': 'true' },
            el('path', { d: 'm22 2-7 20-4-9-9-4Z' }),
            el('path', { d: 'M22 2 11 13' })
        );
    }

    reg(
        'imidjstroy/contact',
        'Форма заявки',
        'email',
        {
            title: { type: 'string', default: 'Оставить заявку' },
            lead: { type: 'string', default: 'Оставьте заявку и мы свяжемся с вами в ближайшее время' },
            buttonText: { type: 'string', default: 'Отправить' }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var editorData = window.imidjstroyHomeEditor || {};
            var phone1 = editorData.phone || '';
            var phone2 = editorData.phone2 || '';
            var address = editorData.address || '';

            return el(
                'section',
                { className: 'home-contact imidjstroy-contact-editor' },
                el(
                    'div',
                    { className: 'container' },
                    el(
                        'div',
                        { className: 'home-contact__grid' },
                        el(
                            'div',
                            { className: 'home-contact__info' },
                            el(RichText, {
                                tagName: 'h2',
                                className: 'home-contact__title',
                                value: a.title,
                                allowedFormats: [],
                                placeholder: 'Оставить заявку',
                                onChange: function (v) { s({ title: v }); }
                            }),
                            el(RichText, {
                                tagName: 'p',
                                className: 'home-contact__lead',
                                value: a.lead,
                                allowedFormats: [],
                                placeholder: 'Описание секции',
                                onChange: function (v) { s({ lead: v }); }
                            }),
                            el(
                                'ul',
                                { className: 'home-contact__contacts' },
                                phone1 ? el('li', null, contactPhoneIconNode(), el('span', null, phone1)) : null,
                                phone2 ? el('li', null, contactPhoneIconNode(), el('span', null, phone2)) : null,
                                address ? el('li', null, contactLocationIconNode(), el('span', null, address)) : null
                            )
                        ),
                        el(
                            'div',
                            { className: 'home-contact__form-wrap' },
                            el(
                                'div',
                                { className: 'home-contact__form', 'aria-label': 'Предпросмотр формы. Отправка в редакторе отключена.' },
                                el(
                                    'div',
                                    { className: 'home-contact__row' },
                                    el(
                                        'div',
                                        { className: 'home-contact__field' },
                                        el('label', null, 'ФИО *'),
                                        el('input', { type: 'text', placeholder: 'ФИО', readOnly: true, tabIndex: -1 })
                                    ),
                                    el(
                                        'div',
                                        { className: 'home-contact__field' },
                                        el('label', null, 'Телефон *'),
                                        el('input', { type: 'tel', placeholder: '+7', readOnly: true, tabIndex: -1 })
                                    )
                                ),
                                el(
                                    'div',
                                    { className: 'home-contact__field' },
                                    el('label', null, 'Сообщение'),
                                    el('textarea', { placeholder: 'Сообщение', rows: 4, readOnly: true, tabIndex: -1 })
                                ),
                                el(
                                    'div',
                                    { className: 'home-contact__submit imidjstroy-contact-editor__submit' },
                                    contactSendIconNode(),
                                    el(RichText, {
                                        tagName: 'span',
                                        className: 'js-contact-submit-text',
                                        value: a.buttonText,
                                        allowedFormats: [],
                                        placeholder: 'Отправить',
                                        onChange: function (v) { s({ buttonText: v }); }
                                    })
                                )
                            )
                        )
                    )
                )
            );
        }
    );

    reg(
        'imidjstroy/cta',
        'Баннер / CTA',
        'megaphone',
        {
            title: { type: 'string', default: 'Нужна консультация?' },
            text: { type: 'string', default: 'Поможем подобрать материалы под вашу задачу.' },
            buttonText: { type: 'string', default: 'Связаться с нами' },
            buttonUrl: { type: 'string', default: '/contacts/' }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            return shell(
                'Баннер / CTA',
                'Компактный призыв к действию.',
                el(
                    'div',
                    { className: 'imidjstroy-editor-fields' },
                    el(Text, { label: 'Заголовок', value: a.title, onChange: function (v) { s({ title: v }); } }),
                    el(Textarea, { label: 'Текст', value: a.text, onChange: function (v) { s({ text: v }); } }),
                    el(Text, { label: 'Кнопка', value: a.buttonText, onChange: function (v) { s({ buttonText: v }); } }),
                    el(Text, { label: 'Ссылка', value: a.buttonUrl, onChange: function (v) { s({ buttonUrl: v }); } })
                )
            );
        }
    );

    reg(
        'imidjstroy/text',
        'Текстовый блок',
        'text',
        {
            eyebrow: { type: 'string', default: '' },
            title: { type: 'string', default: 'Заголовок блока' },
            content: { type: 'string', default: 'Добавьте текст в редакторе главной страницы.' }
        },
        function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            return shell(
                'Текстовый блок',
                'Для свободного информационного раздела.',
                el(
                    'div',
                    { className: 'imidjstroy-editor-fields' },
                    el(Text, { label: 'Надзаголовок', value: a.eyebrow, onChange: function (v) { s({ eyebrow: v }); } }),
                    el(Text, { label: 'Заголовок', value: a.title, onChange: function (v) { s({ title: v }); } }),
                    el(Textarea, { label: 'Текст', value: a.content, onChange: function (v) { s({ content: v }); } })
                )
            );
        }
    );


    /* =========================================================
       FLEXIBLE SECTION — native Gutenberg composition
       ========================================================= */
    register('imidjstroy/section', {
        apiVersion: 2,
        title: 'Свободная секция',
        description: 'Свободная компоновка: тексты, кнопки, изображения, колонки и другие Gutenberg-элементы.',
        icon: 'layout',
        category: 'imidjstroy',
        attributes: {
            background: { type: 'string', default: 'white' },
            padding: { type: 'string', default: 'large' },
            contentWidth: { type: 'string', default: 'container' },
            backgroundUrl: { type: 'string', default: '' },
            backgroundId: { type: 'number', default: 0 },
            overlay: { type: 'number', default: 0 },
            verticalAlign: { type: 'string', default: 'center' }
        },
        supports: {
            html: false,
            anchor: true
        },
        edit: function (props) {
            var a = props.attributes;
            var s = props.setAttributes;
            var classes = [
                'imidjstroy-flex-section',
                'imidjstroy-flex-section--' + a.background,
                'imidjstroy-flex-section--pad-' + a.padding,
                'imidjstroy-flex-section--valign-' + a.verticalAlign
            ];
            if (a.backgroundUrl) classes.push('has-background-image');

            var style = {
                '--imidjstroy-section-overlay': String((a.overlay || 0) / 100)
            };
            if (a.backgroundUrl) {
                style.backgroundImage = 'url("' + a.backgroundUrl.replace(/"/g, '%22') + '")';
            }

            var blockProps = useBlockProps({
                className: classes.join(' '),
                style: style
            });

            var innerAllowed = [
                'core/heading', 'core/paragraph', 'core/image',
                'core/buttons', 'core/button', 'core/columns', 'core/column',
                'core/group', 'core/list', 'core/list-item', 'core/gallery',
                'core/media-text', 'core/quote', 'core/separator', 'core/spacer',
                'imidjstroy/button', 'imidjstroy/contact-item', 'imidjstroy/row',
                'imidjstroy/categories', 'imidjstroy/product-section',
                'imidjstroy/news', 'imidjstroy/gallery', 'imidjstroy/contact'
            ];

            var innerBlocksProps = useInnerBlocksProps(
                {
                    className: 'imidjstroy-flex-section__inner imidjstroy-flex-section__inner--' + a.contentWidth
                },
                {
                    allowedBlocks: innerAllowed,
                    templateLock: false,
                    template: [
                        [ 'core/heading', { level: 2, placeholder: 'Заголовок секции' } ],
                        [ 'core/paragraph', { placeholder: 'Добавьте текст или вставьте другой элемент через +' } ]
                    ],
                    renderAppender: function () {
                        return el(InnerBlocks.ButtonBlockAppender);
                    }
                }
            );

            var mediaControl = el(
                MediaUploadCheck,
                null,
                el(MediaUpload, {
                    allowedTypes: ['image'],
                    value: a.backgroundId,
                    onSelect: function (media) {
                        s({
                            backgroundId: media && media.id ? media.id : 0,
                            backgroundUrl: media && media.url ? media.url : ''
                        });
                    },
                    render: function (obj) {
                        return el(
                            Fragment,
                            null,
                            el(Button, { variant: 'secondary', onClick: obj.open }, a.backgroundUrl ? 'Изменить фон' : 'Выбрать фон'),
                            a.backgroundUrl ? el(Button, { isDestructive: true, variant: 'tertiary', onClick: function () { s({ backgroundId: 0, backgroundUrl: '' }); } }, 'Убрать фон') : null
                        );
                    }
                })
            );

            return el(
                Fragment,
                null,
                el(
                    InspectorControls,
                    null,
                    el(
                        Panel,
                        { title: 'Секция', initialOpen: true },
                        el(Select, {
                            label: 'Фон',
                            value: a.background,
                            options: [
                                { label: 'Белый', value: 'white' },
                                { label: 'Светло-серый', value: 'gray' },
                                { label: 'Светло-зелёный', value: 'soft-green' },
                                { label: 'Зелёный', value: 'green' },
                                { label: 'Тёмный', value: 'dark' }
                            ],
                            onChange: function (v) { s({ background: v }); }
                        }),
                        el(Select, {
                            label: 'Вертикальные отступы',
                            value: a.padding,
                            options: [
                                { label: 'Без отступов', value: 'none' },
                                { label: 'Маленькие', value: 'small' },
                                { label: 'Средние', value: 'medium' },
                                { label: 'Большие', value: 'large' },
                                { label: 'Очень большие', value: 'xlarge' }
                            ],
                            onChange: function (v) { s({ padding: v }); }
                        }),
                        el(Select, {
                            label: 'Ширина содержимого',
                            value: a.contentWidth,
                            options: [
                                { label: 'Контейнер сайта', value: 'container' },
                                { label: 'Узкая', value: 'narrow' },
                                { label: 'На всю ширину', value: 'wide' }
                            ],
                            onChange: function (v) { s({ contentWidth: v }); }
                        }),
                        el('div', { className: 'imidjstroy-editor-media-control' }, mediaControl),
                        a.backgroundUrl ? el(Range, { label: 'Затемнение фона', value: a.overlay, min: 0, max: 80, step: 5, onChange: function (v) { s({ overlay: v }); } }) : null
                    )
                ),
                el(
                    'section',
                    blockProps,
                    el('div', innerBlocksProps)
                )
            );
        },
        save: function (props) {
            var a = props.attributes;
            var classes = [
                'imidjstroy-flex-section',
                'imidjstroy-flex-section--' + a.background,
                'imidjstroy-flex-section--pad-' + a.padding,
                'imidjstroy-flex-section--valign-' + a.verticalAlign
            ];
            if (a.backgroundUrl) classes.push('has-background-image');

            var style = {
                '--imidjstroy-section-overlay': String((a.overlay || 0) / 100)
            };
            if (a.backgroundUrl) {
                style.backgroundImage = 'url("' + a.backgroundUrl.replace(/"/g, '%22') + '")';
            }

            var blockProps = useBlockProps.save({
                className: classes.join(' '),
                style: style
            });

            var innerBlocksProps = useInnerBlocksProps.save({
                className: 'imidjstroy-flex-section__inner imidjstroy-flex-section__inner--' + a.contentWidth
            });

            return el(
                'section',
                blockProps,
                el('div', innerBlocksProps)
            );
        }
    });

})(window.wp);
