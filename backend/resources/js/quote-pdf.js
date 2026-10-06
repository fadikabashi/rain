import pdfMake from 'pdfmake/build/pdfmake';
import robotoVfs from 'pdfmake/build/vfs_fonts';

// vfs_fonts registers Roboto only; ensure globalVfs matches pdfMake.vfs for createPdf().
pdfMake.vfs = robotoVfs;
if (typeof pdfMake.addVirtualFileSystem === 'function') {
    pdfMake.addVirtualFileSystem(robotoVfs);
}

pdfMake.fonts = {
    Roboto: {
        normal: 'Roboto-Regular.ttf',
        bold: 'Roboto-Medium.ttf',
        italics: 'Roboto-Italic.ttf',
        bolditalics: 'Roboto-MediumItalic.ttf',
    },
};

const BORDER = '#000000';
const RED = '#b71c1c';
const FOOTER_BG = '#1a237e';

/**
 * @param {unknown} value
 * @returns {string}
 */
function pdfText(value) {
    if (value === null || value === undefined) {
        return '';
    }
    return String(value)
        .replace(/[\u200B-\u200D\uFEFF]/g, '')
        .replace(/[\u200E\u200F\u202A-\u202E\u2066-\u2069]/g, '')
        .replace(/\u00A0/g, ' ')
        .trim();
}

function formatMoney(value) {
    const n = Number(value);
    if (Number.isNaN(n)) {
        return '0.00';
    }
    return n.toFixed(2);
}

function blobToDataUrl(blob) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
    });
}

async function imageCellFromUrl(url) {
    if (!url) {
        return { text: '—', font: 'Roboto', alignment: 'center', margin: [0, 12] };
    }
    try {
        const response = await fetch(url, { credentials: 'same-origin' });
        if (!response.ok) {
            return { text: '—', font: 'Roboto', alignment: 'center', margin: [0, 12] };
        }
        const blob = await response.blob();
        const dataUrl = await blobToDataUrl(blob);
        return { image: dataUrl, width: 34, alignment: 'center', margin: [0, 2] };
    } catch {
        return { text: '—', font: 'Roboto', alignment: 'center', margin: [0, 12] };
    }
}

function headerCell(label) {
    return {
        text: pdfText(label),
        font: 'Roboto',
        fontSize: 7,
        alignment: 'center',
        margin: [1, 2, 1, 2],
    };
}

function descriptionCell(row) {
    const parts = [];
    if (row.name) {
        parts.push({
            text: pdfText(row.name),
            font: 'Roboto',
            bold: true,
            fontSize: 10,
            margin: [0, 0, 0, 2],
        });
    }
    if (row.description) {
        parts.push({
            text: pdfText(row.description),
            font: 'Roboto',
            fontSize: 8,
            color: '#333333',
        });
    }
    if (row.line_note) {
        parts.push({
            text: pdfText(row.line_note),
            font: 'Roboto',
            italics: true,
            fontSize: 8,
            color: '#555555',
        });
    }
    if (parts.length === 0) {
        return { text: '—', font: 'Roboto', margin: [2, 2] };
    }
    return { stack: parts, margin: [2, 2] };
}

function tableLayout() {
    return {
        hLineWidth() {
            return 0.5;
        },
        vLineWidth() {
            return 0.5;
        },
        hLineColor() {
            return BORDER;
        },
        vLineColor() {
            return BORDER;
        },
    };
}

async function buildDocDefinition(data) {
    const l = data.labels;
    const b = data.branding;
    const c = data.customer;

    const headerStack = [];

    const titleRow = {
        columns: [],
        margin: [0, 0, 0, 6],
    };

    if (b.logo_data_url) {
        titleRow.columns.push({
            image: b.logo_data_url,
            width: 55,
            margin: [0, 0, 10, 0],
        });
    }

    titleRow.columns.push({
        stack: [
            {
                text: pdfText(b.company_name),
                font: 'Roboto',
                fontSize: 11,
                alignment: 'center',
                bold: true,
            },
        ],
        width: '*',
    });

    headerStack.push(titleRow);
    headerStack.push({
        table: {
            widths: ['*'],
            body: [
                [
                    {
                        text: pdfText(b.tagline),
                        font: 'Roboto',
                        color: '#ffffff',
                        bold: true,
                        alignment: 'center',
                        fillColor: RED,
                        margin: [6, 8, 6, 8],
                        fontSize: 10,
                    },
                ],
            ],
        },
        layout: 'noBorders',
    });

    const created = data.meta?.created_at_iso ? new Date(data.meta.created_at_iso) : new Date();
    const dateStr = created.toLocaleDateString('en-GB');

    const customerGrid = {
        table: {
            widths: ['*', 80, '*'],
            body: [
                [
                    {
                        stack: [
                            {
                                text: `${pdfText(l.date)}: ${pdfText(dateStr)}`,
                                font: 'Roboto',
                                fontSize: 8,
                                bold: true,
                            },
                            {
                                text: `${pdfText(l.phone)}: ${pdfText(c.phone)}`,
                                font: 'Roboto',
                                fontSize: 8,
                                bold: true,
                                margin: [0, 6, 0, 0],
                            },
                        ],
                        margin: [6, 6],
                    },
                    {
                        text: '',
                        border: [true, true, true, true],
                        margin: [6, 6],
                    },
                    {
                        stack: [
                            {
                                text: `${pdfText(l.customer)}: ${pdfText(c.name)}`,
                                font: 'Roboto',
                                fontSize: 9,
                                alignment: 'right',
                            },
                            {
                                text: `${pdfText(l.location)}: ${pdfText(c.location_line) || '—'}`,
                                font: 'Roboto',
                                fontSize: 8,
                                alignment: 'right',
                                margin: [0, 4, 0, 0],
                            },
                        ],
                        margin: [6, 6],
                    },
                ],
            ],
        },
        layout: tableLayout(),
        margin: [0, 0, 0, 10],
    };

    const content = [
        {
            table: {
                widths: ['*'],
                body: [
                    [
                        {
                            stack: headerStack,
                            border: [true, true, true, false],
                            margin: [8, 8, 8, 4],
                        },
                    ],
                ],
            },
            layout: {
                hLineWidth(i) {
                    return i === 0 ? 0.8 : 0;
                },
                vLineWidth(i) {
                    return i === 0 || i === 1 ? 0.8 : 0;
                },
                hLineColor() {
                    return BORDER;
                },
                vLineColor() {
                    return BORDER;
                },
            },
        },
        customerGrid,
    ];

    for (const section of data.sections) {
        content.push({
            text: pdfText(section.title),
            font: 'Roboto',
            fontSize: 11,
            alignment: 'center',
            bold: true,
            margin: [0, 8, 0, 4],
        });

        const headerRow = [
            headerCell(l.no),
            headerCell(l.code),
            headerCell(l.item),
            headerCell(l.description),
            headerCell(l.qty),
            headerCell(l.unit_price),
            headerCell(l.amount),
        ];

        const body = [headerRow];

        for (const row of section.rows) {
            const img = await imageCellFromUrl(row.image_url);
            body.push([
                { text: String(row.index), font: 'Roboto', alignment: 'center', margin: [0, 4] },
                { text: pdfText(row.code), font: 'Roboto', alignment: 'center', margin: [0, 4] },
                img,
                descriptionCell(row),
                { text: String(row.quantity), font: 'Roboto', alignment: 'center', margin: [0, 4] },
                { text: formatMoney(row.unit_price), font: 'Roboto', alignment: 'right', margin: [0, 4] },
                { text: formatMoney(row.line_total), font: 'Roboto', alignment: 'right', margin: [0, 4] },
            ]);
        }

        const totalCols = 7;
        const totalRow = [
            {
                text: pdfText(l.total),
                colSpan: totalCols - 1,
                font: 'Roboto',
                alignment: 'right',
                bold: true,
                margin: [4, 4],
            },
        ];
        for (let i = 0; i < totalCols - 2; i += 1) {
            totalRow.push({});
        }
        totalRow.push({
            text: formatMoney(section.section_total),
            font: 'Roboto',
            alignment: 'right',
            bold: true,
            margin: [4, 4],
        });
        body.push(totalRow);

        content.push({
            table: {
                widths: [26, 42, 44, '*', 30, 48, 52],
                body,
                headerRows: 1,
            },
            layout: tableLayout(),
        });
    }

    content.push({
        margin: [0, 14, 0, 0],
        table: {
            widths: ['*', 60],
            body: [
                [
                    {
                        text: pdfText(l.grand_total),
                        font: 'Roboto',
                        bold: true,
                        alignment: 'right',
                        margin: [4, 6],
                    },
                    {
                        text: `${formatMoney(data.grand_total)} ${pdfText(l.currency)}`,
                        font: 'Roboto',
                        bold: true,
                        alignment: 'right',
                        margin: [4, 6],
                    },
                ],
            ],
        },
        layout: tableLayout(),
    });

    return {
        pageMargins: [36, 40, 36, 56],
        defaultStyle: {
            font: 'Roboto',
            fontSize: 9,
            color: '#111111',
        },
        content,
        footer(currentPage, pageCount) {
            return {
                stack: [
                    {
                        text: `${pdfText(b.phone)}  |  WhatsApp: ${pdfText(b.whatsapp)}`,
                        font: 'Roboto',
                        alignment: 'center',
                        fontSize: 8,
                        color: '#ffffff',
                        fillColor: FOOTER_BG,
                        margin: [36, 6, 36, 2],
                    },
                    {
                        text: pdfText(b.address),
                        font: 'Roboto',
                        alignment: 'center',
                        fontSize: 8,
                        color: '#ffffff',
                        fillColor: FOOTER_BG,
                        margin: [36, 0, 36, 8],
                    },
                    {
                        text: `${currentPage} / ${pageCount}`,
                        font: 'Roboto',
                        alignment: 'center',
                        fontSize: 7,
                        margin: [0, 2, 0, 0],
                    },
                ],
            };
        },
    };
}

/**
 * @param {string} dataUrl Absolute signed JSON URL
 */
export async function downloadQuotePdfFromDataUrl(dataUrl) {
    const response = await fetch(dataUrl, {
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
    if (!response.ok) {
        throw new Error(
            `Could not load quote data for PDF (HTTP ${response.status}).`,
        );
    }
    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error(
            'Quote PDF response was not JSON (often a redirect to login). Check APP_URL (https), session cookies, and TRUST_ALL_PROXIES on your server.',
        );
    }
    if (!data.meta || !data.meta.quote_id) {
        throw new Error('Invalid quote data.');
    }
    const doc = await buildDocDefinition(data);
    const filename = `quote-${data.meta.quote_id}.pdf`;
    pdfMake.createPdf(doc).download(filename);
}

function bindDownloadButtons() {
    document.querySelectorAll('[data-quote-pdf-url]').forEach((el) => {
        if (el.dataset.quotePdfBound === '1') {
            return;
        }
        el.dataset.quotePdfBound = '1';
        el.addEventListener('click', async () => {
            const url = el.getAttribute('data-quote-pdf-url');
            if (!url) {
                return;
            }
            const prev = el.textContent;
            el.disabled = true;
            el.textContent = el.getAttribute('data-quote-pdf-loading') || '…';
            try {
                await downloadQuotePdfFromDataUrl(url);
            } catch (e) {
                window.alert(e.message || 'PDF failed');
            } finally {
                el.disabled = false;
                el.textContent = prev;
            }
        });
    });
}

if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', bindDownloadButtons);
}

window.downloadQuotePdfFromDataUrl = downloadQuotePdfFromDataUrl;
