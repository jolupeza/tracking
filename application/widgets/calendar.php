<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CalendarWidget extends Widget
{
	private $_confCalendar;
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('widget');

		$this->_confCalendar = array(
			'start_day'			=>	'monday',
			'show_next_prev'	=>	true,
			'next_prev_url'		=>	base_url() . 'reminders/getDisplayCalendar/display/calendar'
		);

		$this->_confCalendar['template'] = $this->_templateCalendar();
	}

	private function _templateCalendar()
	{
		$template = '{table_open}<table border="0" cellpadding="0" cellspacing="0" class="tb-calendar">{/table_open}'

					.'{heading_row_start}<tr>{/heading_row_start}'

   					.'{heading_previous_cell}<th><a class="prev_calendar" href="{previous_url}">&laquo;</a></th>{/heading_previous_cell}'
   					.'{heading_title_cell}<th id="data_month_year" colspan="{colspan}"><a href="#" id="open-sel-month">{heading}</a></th>{/heading_title_cell}'
   					.'{heading_next_cell}<th><a class="next_calendar" href="{next_url}">&raquo;</a></th>{/heading_next_cell}'

   					.'{heading_row_end}</tr>{/heading_row_end}'

					.'{week_row_start}<tr>{/week_row_start}'
   					.'{week_day_cell}<td>{week_day}</td>{/week_day_cell}'
   					.'{week_row_end}</tr>{/week_row_end}'

					.'{cal_row_start}<tr class="days">{/cal_row_start}'
   					.'{cal_cell_start}<td class="day">{/cal_cell_start}'

   					.'{cal_cell_content}'
   						.'<div class="day_num">{day}</div>'
   						.'<div class="content-day">{content}</div>'
   					.'{/cal_cell_content}'
   					.'{cal_cell_content_today}'
   						.'<div class="day_num highlight">{day}</div>'
   						.'<div class="content-day">{content}</div>'
   					.'{/cal_cell_content_today}'

					.'{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}'
   					.'{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}'

					.'{cal_cell_blank}&nbsp;{/cal_cell_blank}'

					.'{cal_cell_end}</td>{/cal_cell_end}'
   					.'{cal_row_end}</tr>{/cal_row_end}'

					.'{table_close}</table>{/table_close}';

		return $template;
	}

	public function displayCalendar($view, $model, $inverse = null, $year = null, $month = null, $day = null)
	{
		if (!$year) {
			$year = date('Y');
		}

		if (!$month) {
			$month = date('m');
		}

		$result = $this->_getReminders($model, $year, $month, $day);

		$arr_days = array();
		if ($result) {
			$data['reminders'] = $result;

			foreach ($result as $row) {
				$day = date('d', strtotime($row->published_at));

				if (array_key_exists($day, $arr_days)) {
					if (!strstr($arr_days[intval($day)], '<span class="' . $row->post_type . '"></span>')) {
						$arr_days[intval($day)] .= '<span class="' . $row->post_type . '"></span>';
					}
				} else {
					$arr_days[intval($day)] = '<span class="' . $row->post_type . '"></span>';
				}
			}

		}

		$this->CI->load->library('calendar', $this->_confCalendar);
		$data['calendar'] = $this->CI->calendar->generate($year, $month, $arr_days);
		$data['inverse'] = $inverse;
		$data['year'] = $year;
		$data['month'] = $month;
		return $this->render($view, $data);
	}

	private function _getReminders($model, $year, $month, $day)
	{
		$this->CI->load->model('widgets/' . ucfirst($model) . '_model', $model);
		$result = $this->CI->$model->getReminders($year, $month, $day);

		if ($result && count($result) > 0) {
			return $result;
		}

		return FALSE;
	}

	public function getConfig($menu)
    {
        $menus['sidebar'] = array(
            'position' => 'sidebar',
            'show' => 'all',
            'hide' => array('inicio')
        );

        /*$menus['top'] = array(
            'position' => 'top',
            'show' => 'all'
        );*/

        return $menus[$menu];
    }
}