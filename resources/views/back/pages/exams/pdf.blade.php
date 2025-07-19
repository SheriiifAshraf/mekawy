<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--favicon-->
    <link rel="icon" href="{{ url('back/assets/images/logo.png') }}" type="image/png" />

    <title> تنزيل {{ $exam->name }} </title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            /* Set direction to RTL */
            text-align: right;
            /* Align text to the right */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: right;
            /* Align text in cells to the right */
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .badge {
            padding: 4px 8px;
            color: #fff;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .bg-success {
            background-color: #28a745;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            text-align: center;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-bottom: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <button id="exportPDF" style="text-decoration: none; background-color: transparent;"><img
                src="{{ url('back/assets/images/pdf.png') }}" width="70" height="70" alt=""></button>


        <div id="contentToExport" class="questions-container" style="margin-top: 20px;">
            @foreach ($questions as $key => $question)
                <div class="question-block"
                    style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
                    <!-- السؤال -->
                    <div class="question-text" style="margin-bottom: 15px;">
                        <h3 style="font-size: 20px; font-weight: bold; color: #333;">
                            {{ $key + 1 }}. {{ $question->question }}
                        </h3>
                        <!-- صورة السؤال (إن وجدت) -->
                        @if ($question->getFirstMedia('questions'))
                            <div class="question-image" style="margin-top: 10px;">
                                <img src="{{ asset('storage/' . $question->getFirstMedia('questions')->id . '/' . $question->getFirstMedia('questions')->file_name) }}"
                                    alt="Question Image"
                                    style="max-width: 100%; height: auto; border-radius: 10px; border: 1px solid #ddd;">
                            </div>
                        @endif
                    </div>

                    <!-- الإجابات -->
                    <div class="answers-list">
                        @php
                            $rank = 1;
                        @endphp
                        @foreach ($question->answers as $answer)
                            <div class="answer-item"
                                style="margin-bottom: 20px; padding: 15px; border: 2px solid #ddd; border-radius: 10px; background-color: #fff;">
                                <!-- رقم الإجابة والنص -->
                                <div class="answer-text"
                                    style="font-size: 18px; line-height: 1.6; color: #333; display: flex; align-items: flex-start; gap: 20px;">
                                    <span style="font-weight: bold; font-size: 20px;">{{ $rank }}.</span>
                                    <span>{{ $answer->answer }}</span>
                                    @if ($answer->is_correct)
                                        <span class="badge bg-success"
                                            style="margin-left: 15px; font-size: 16px; padding: 6px 12px;">صحيحة</span>
                                    @endif
                                </div>
                                <!-- صورة الإجابة (إن وجدت) -->
                                @if ($answer->getFirstMedia('answers'))
                                    <div class="answer-image" style="margin-top: 10px;">
                                        <img src="{{ asset('storage/' . $answer->getFirstMedia('answers')->id . '/' . $answer->getFirstMedia('answers')->file_name) }}"
                                            alt="Answer Image"
                                            style="max-width: 100%; height: auto; border-radius: 10px; border: 1px solid #ddd;">
                                    </div>
                                @endif
                            </div>
                            @php
                                $rank++;
                            @endphp
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>



        </table>
    </div>

    <!-- Include html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.getElementById('exportPDF').addEventListener('click', function() {
            const element = document.getElementById('contentToExport');

            html2canvas(element, {
                useCORS: true
            }).then(canvas => {
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 210;
                const pageHeight = 295;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('exam-questions.pdf');
            });
        });
    </script>
</body>

</html>
