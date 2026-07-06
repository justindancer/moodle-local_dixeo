<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language strings for the Dixeo plugin.
 *
 * @package    local_dixeo
 * @copyright  2025 Edunao SAS (contact@edunao.com)
 * @author     Pierre FACQ <pierre.facq@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General.
$string['pluginname'] = 'Dixeo AI';
$string['pluginname_desc'] = 'Integração Dixeo AI para geração e edição inteligente de conteúdo.';

// Capabilities.
$string['dixeo:manage'] = 'Gerir definições Dixeo e ver relatórios';
$string['dixeo:generate'] = 'Gerar novos módulos com IA (página, etiqueta, questionário, glossário)';
$string['dixeo:edit'] = 'Editar módulos existentes com IA';
$string['dixeo:create'] = 'Criar cursos com o Designer de Cursos Dixeo';
$string['dixeo:viewusage'] = 'Ver relatórios de utilização de créditos';

// Settings page.
$string['api_configuration'] = 'Configuração da API';
$string['api_configuration_desc'] = 'Configurar a ligação à API Dixeo AI.';
$string['api_url'] = 'URL da API';
$string['api_url_desc'] = 'URL base da API Dixeo. Predefinido: https://api.dixeo.com';
$string['api_key'] = 'Chave API';
$string['api_key_desc'] = 'A sua chave API Dixeo. Obtenha uma no painel Dixeo.';
$string['namespace'] = 'Espaço de nomes';
$string['namespace_desc'] = 'Apenas necessário quando vários sites Moodle partilham a mesma chave API. Cada site deve usar um espaço de nomes diferente (ex.: "production", "staging", "site1") para manter os dados separados. Deixe "default" se este for o único site a usar esta chave API.';
$string['image_generation'] = 'Geração de imagens';
$string['image_generation_desc'] = 'Controla a disponibilidade da geração e edição de imagens por IA para imagens do curso e das secções.';
$string['image_generation_enabled'] = 'Ativar geração de imagens';
$string['image_generation_enabled_desc'] = 'Quando desativado, todos os pedidos para gerar ou editar imagens são bloqueados.';
$string['image_generation_course_mode'] = 'Imagens do curso';
$string['image_generation_course_mode_desc'] = 'Controla as ações de imagem por IA para a imagem de resumo do curso.';
$string['image_generation_section_mode'] = 'Imagens da secção';
$string['image_generation_section_mode_desc'] = 'Controla as ações de imagem por IA para imagens de capítulo ou secção.';
$string['image_generation_mode_disabled'] = 'Desativado';
$string['image_generation_mode_generate'] = 'Gerar';
$string['image_generation_mode_generate_edit'] = 'Gerar e editar';
$string['credit_information'] = 'Informação de créditos';
$string['current_balance'] = 'Saldo atual';
$string['current_balance_desc'] = 'O seu saldo de créditos Dixeo atual. Os créditos são usados para operações de IA.';
$string['credit_report'] = 'Relatório de créditos';
$string['view_credit_report'] = 'Ver relatório detalhado de créditos';
$string['configure_api'] = 'Configurar API';

// Credit balance.
$string['state_active'] = 'Ativo';
$string['state_frozen'] = 'Congelado';
$string['state_suspended'] = 'Suspenso';

// Credit report page.
$string['usage_statistics'] = 'Estatísticas de utilização';
$string['this_week_usage'] = 'Esta semana';
$string['week_total'] = 'Total desta semana';
$string['recent_transactions'] = 'Histórico de transações';
$string['total_used'] = 'Total utilizado';
$string['average_per_period'] = 'Média por {$a}';
$string['data_points'] = 'Pontos de dados';
$string['no_usage_data'] = 'Sem dados de utilização disponíveis para o período selecionado.';
$string['no_transactions'] = 'Nenhuma transação encontrada.';
$string['usage_chart_label'] = 'Utilização de créditos';

// Day names (short).
$string['day_mon'] = 'Seg';
$string['day_tue'] = 'Ter';
$string['day_wed'] = 'Qua';
$string['day_thu'] = 'Qui';
$string['day_fri'] = 'Sex';
$string['day_sat'] = 'Sáb';
$string['day_sun'] = 'Dom';

// Day names (full).
$string['day_monday'] = 'Segunda-feira';
$string['day_tuesday'] = 'Terça-feira';
$string['day_wednesday'] = 'Quarta-feira';
$string['day_thursday'] = 'Quinta-feira';
$string['day_friday'] = 'Sexta-feira';
$string['day_saturday'] = 'Sábado';
$string['day_sunday'] = 'Domingo';

// Periods.
$string['period'] = 'Período';
$string['period_day'] = 'Diário';
$string['period_week'] = 'Semanal';
$string['period_month'] = 'Mensal';

// Transaction types.
$string['transaction_type_purchase'] = 'Compra';
$string['transaction_type_deduction'] = 'Utilização';
$string['transaction_type_refund'] = 'Reembolso';
$string['transaction_type_reset'] = 'Renovação';

// Table headers.
$string['date'] = 'Data';
$string['type'] = 'Tipo';
$string['description'] = 'Descrição';
$string['amount'] = 'Montante';

// Pagination.
$string['pagination'] = 'Navegação de páginas';
$string['page_x_of_y'] = 'Página {$a->current} de {$a->total}';

// Warnings and errors.
$string['api_key_not_configured'] = 'A chave API Dixeo não está configurada. Configure-a nas definições do plugin.';
$string['api_error'] = 'Erro da API: {$a}';
$string['account_frozen_warning'] = 'A sua conta está congelada devido a saldo de créditos insuficiente. Adicione créditos para continuar a usar as funcionalidades Dixeo AI.';
$string['account_suspended_warning'] = 'A sua conta foi suspensa. Contacte o suporte Dixeo para assistência.';

// Errors (used in exceptions).
$string['error:authentication'] = 'Autenticação falhou. Verifique a sua chave API.';
$string['error:payment_required'] = 'Créditos insuficientes. Adicione créditos para continuar.';
$string['error:rate_limit'] = 'Limite de pedidos excedido. Aguarde antes de fazer mais pedidos.';
$string['error:validation'] = 'Pedido inválido: {$a}';
$string['error:job_not_found'] = 'O trabalho solicitado não foi encontrado.';
$string['error:upstream_ai'] = 'Erro do serviço de IA. Tente novamente mais tarde.';
$string['error:job_failed'] = 'Falha no processamento do trabalho: {$a}';
$string['error:connection'] = 'Falha na ligação à API Dixeo. Verifique a sua ligação de rede.';
$string['error:timeout'] = 'A operação expirou. Pode verificar o estado do trabalho mais tarde.';
$string['error:notslideshow'] = 'O módulo da disciplina não é uma atividade de apresentação.';
$string['error:slidenotinslideshow'] = 'O diapositivo solicitado não pertence a esta apresentação.';

// Overview page.
$string['overview'] = 'Visão geral Dixeo';
$string['credit_balance'] = 'Saldo de créditos';
$string['credits'] = 'créditos';

// Privacy.
$string['privacy:metadata'] = 'O plugin Dixeo envia o conteúdo do curso para a API Dixeo AI para processamento mas não armazena dados pessoais localmente.';

// DSL errors.
$string['dsl_error'] = 'Falha na criação do módulo: {$a}';

// Quiz question feedback.
$string['feedback_correct'] = 'Muito bem, acertou nesta resposta. Continue assim!';
$string['feedback_partial'] = 'Está no caminho certo. Reveja o material e vai conseguir.';
$string['feedback_incorrect'] = 'Não foi desta vez. Rever o tema vai ajudá-lo a melhorar.';

// Tasks.
$string['task_cleanup_jobs'] = 'Limpar registos antigos de trabalhos';
$string['task_process_file_sync'] = 'Processar sincronização de ficheiros Dixeo';
$string['task_poll_image_generation'] = 'Consultar a tarefa de geração de imagens Dixeo';
$string['dixeo_course_image_unsupported_type'] = 'Tipo de imagem gerada não suportado.';
$string['dixeo_image_job_empty_result'] = 'A tarefa de imagem não devolveu dados de imagem.';
$string['dixeo_image_generation_disabled'] = 'A geração de imagens está desativada nas definições do site.';
$string['dixeo_pluginfile_not_found'] = 'Não foi possível ler o ficheiro de imagem a partir do armazenamento.';

// File sync.
$string['filesync_title'] = 'Sincronização de ficheiros Dixeo';
$string['filesync_label'] = 'Sincronizar';
$string['filesync_status_none'] = 'Nenhum ficheiro sincronizado';
$string['filesync_status_syncing'] = 'A sincronizar ficheiros...';
$string['filesync_status_synchronized'] = 'Ficheiros sincronizados';
$string['filesync_status_error'] = 'Erro de sincronização';
$string['filesync_status_outdated'] = 'Conteúdo alterado, sincronização necessária';
$string['filesync_status_paused'] = 'Sincronização em pausa';
$string['filesync_status_disabled'] = 'Sincronização desativada';
$string['filesync_enable'] = 'Ativar sincronização';
$string['filesync_pause'] = 'Pausar sincronização';
$string['filesync_disable_remove'] = 'Desativar e limpar dados de sincronização';
$string['filesync_resync'] = 'Sincronizar agora';
$string['filesync_files_count'] = '{$a} ficheiros sincronizados';
$string['filesync_progress'] = '{$a}% concluído';
$string['last_sync'] = 'Última sincronização';
$string['filesync_error_retry'] = 'Será repetido automaticamente';
$string['filesync_failed'] = 'Falha na sincronização de ficheiros: {$a}';
$string['filesync_timeout'] = 'A sincronização de ficheiros expirou antes de os ficheiros do curso serem indexados';
$string['files'] = 'ficheiros';

// Designer structure validation (finalize / course creation).
$string['designerstructurevalidate_failed'] = 'Este curso não pode ser criado até que estes problemas sejam resolvidos:

{$a->details}';
$string['designerstructurevalidate_invalid_root'] = 'Os dados da estrutura do curso são inválidos.';
$string['designerstructurevalidate_sections_not_array'] = 'A lista de secções da estrutura do curso é inválida.';
$string['designerstructurevalidate_section_invalid'] = 'A secção {$a} na estrutura é inválida.';
$string['designerstructurevalidate_modules_not_array'] = 'A lista de módulos da secção {$a} é inválida.';
$string['designerstructurevalidate_module_invalid'] = 'O módulo na posição {$a->module} na secção {$a->section} é inválido.';
$string['designerstructurevalidate_aggregate_prefix_section'] = 'Secção {$a->section}, atividade {$a->module}:';
$string['designerstructurevalidate_aggregate_prefix_section_only'] = 'Secção {$a->section}:';
$string['designerstructurevalidate_course_title_required'] = 'O título do curso é um campo obrigatório.';
$string['designerstructurevalidate_course_title_too_long'] = 'O título do curso deve ter no máximo {$a->max} caracteres.';
$string['designerstructurevalidate_course_summary_too_long'] = 'O resumo do curso é demasiado longo (máximo {$a->max} caracteres).';
$string['designerstructurevalidate_section_title_too_long'] = 'O título da secção é demasiado longo (máximo {$a->max} caracteres).';
$string['designerstructurevalidate_section_summary_too_long'] = 'O resumo da secção é demasiado longo (máximo {$a->max} caracteres).';
$string['designerstructurevalidate_module_type_required'] = 'O tipo de atividade é um campo obrigatório.';
$string['designerstructurevalidate_module_type_not_usable'] = 'O tipo «{$a->type}» não pode ser usado neste site (plugin em falta ou biblioteca de conteúdos necessária).';
$string['designerstructurevalidate_module_title_required'] = 'O título da atividade é um campo obrigatório.';
$string['designerstructurevalidate_module_title_placeholder'] = 'Substitua o título predefinido «Nova página» por um nome de atividade real.';
$string['designerstructurevalidate_module_title_too_long'] = 'O título da atividade é demasiado longo (máximo {$a->max} caracteres).';
$string['designerstructurevalidate_module_summary_placeholder'] = 'Substitua o resumo predefinido por uma descrição real do que esta atividade abrange.';
$string['designerstructurevalidate_module_summary_too_long'] = 'O resumo da atividade é demasiado longo (máximo {$a->max} caracteres).';
$string['designerstructurevalidate_module_instructions_required'] = 'As instruções para a IA são obrigatórias (pelo menos {$a->min} caracteres).';
$string['designerstructurevalidate_module_instructions_too_long'] = 'As instruções são demasiado longas (máximo {$a->max} caracteres).';
$string['designerstructurevalidate_instructions_api_min'] = 'As instruções devem ter pelo menos {$a->min} caracteres.';
$string['designerstructurevalidate_fill_instructions_too_long'] = 'As instruções enviadas à IA são demasiado longas (máximo {$a->max} caracteres).';

// Practice quiz.
$string['practice_quiz_default_title'] = 'Questionário de prática';
$string['practice_quiz_difficulty_easy'] = 'fácil (recordação básica, conceitos simples, adequado para principiantes)';
$string['practice_quiz_difficulty_medium'] = 'médio (profundidade moderada que exige compreensão para além da simples recordação)';
$string['practice_quiz_difficulty_hard'] = 'difícil (aplicação exigente, análise ou síntese de conceitos avançados)';
$string['practice_quiz_scope_course_description'] = 'o curso completo «{$a->name}»';
$string['practice_quiz_scope_section_description'] = 'a secção «{$a->name}»';
$string['practice_quiz_scope_activity_description'] = 'a atividade «{$a->name}»';
$string['practice_quiz_instructions'] = 'Gere um questionário de prática para {$a->scopedescription}.

REQUISITOS OBRIGATÓRIOS — deve segui-los exatamente:
1. NÚMERO DE PERGUNTAS: O array "questions" DEVE conter exatamente {$a->count} perguntas. Não produza {$a->count} menos um, {$a->count} mais um, nem qualquer outro número — exatamente {$a->count}.
2. NÍVEL DE DIFICULDADE: Cada pergunta DEVE ter dificuldade {$a->difficultylabel}.
3. FORMATO: Cada pergunta DEVE ser de escolha múltipla com 3 ou 4 opções de resposta e exatamente uma resposta correta.

Antes de terminar, verifique se o comprimento do array questions é {$a->count} e se todas as perguntas correspondem ao nível de dificuldade {$a->difficulty}.
Concentre-se no contexto do curso fornecido.';
$string['practice_quiz_error_job_not_completed'] = 'O trabalho não está concluído. Estado: {$a->status}';
$string['practice_quiz_error_invalid_result'] = 'Resultado do trabalho inválido.';
$string['practice_quiz_error_wrong_module_type'] = 'O trabalho não é uma geração simplequiz2.';
$string['practice_quiz_error_no_questions'] = 'Não há perguntas no resultado do trabalho.';
$string['generation_output_language'] = 'IDIOMA: Gere todo o conteúdo para o aluno (perguntas, respostas, texto da lição e títulos) em {$a->language}.';

// Teach lesson.
$string['teach_lesson_default_title'] = 'Lição personalizada';
$string['teach_lesson_instructions'] = 'Gere uma lição de módulo Page personalizada para {$a->scopedescription}.

O aluno perguntou:
"{$a->learnerrequest}"

REQUISITOS OBRIGATÓRIOS — DEVE segui-los exatamente:
1. TIPO DE MÓDULO: Produza um módulo Page com um nome claro e descritivo, um breve resumo introdutório (intro) e conteúdo principal rico (content).
2. ESTRUTURA: Organize a lição com títulos claros e secções lógicas. Use exemplos quando útil.
3. PEDIDO DO ALUNO: Responda diretamente ao pedido do aluno — aprofunde o assunto ou explique-o em termos mais simples como pediu.
4. ALINHAMENTO: Baseie a lição no contexto do curso fornecido. Não invente factos que contradigam o material de origem.

Antes de terminar, verifique se o campo content é substantivo e responde diretamente ao pedido do aluno.';
$string['teach_lesson_error_job_not_completed'] = 'O trabalho não está concluído. Estado: {$a->status}';
$string['teach_lesson_error_invalid_result'] = 'Resultado do trabalho inválido.';
$string['teach_lesson_error_wrong_module_type'] = 'O trabalho não é uma geração de página.';
$string['teach_lesson_error_no_content'] = 'Sem conteúdo no resultado do trabalho.';
