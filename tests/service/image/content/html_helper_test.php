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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

namespace local_dixeo\service\image\content;

defined('MOODLE_INTERNAL') || die();

/**
 * @covers \local_dixeo\service\image\content\html_helper
 */
final class html_helper_test extends \advanced_testcase {

    public function test_swap_removes_pending_when_class_precedes_data_attribute(): void {
        $id = 'abc-123';
        $html = '<img src="http://x/y.png" class="img-fluid dixeo-img-gen-pending" data-dixeo-img-gen="' . $id . '" alt="" />';
        $updated = html_helper::swap_img_class_for_placeholder($html, $id, 'dixeo-img-gen-pending', '');
        $this->assertStringNotContainsString('dixeo-img-gen-pending', $updated);
        $this->assertStringContainsString('data-dixeo-img-gen="' . $id . '"', $updated);
        $this->assertStringContainsString('class="img-fluid"', $updated);
    }

    public function test_normalize_legacy_intro_pluginfile_urls(): void {
        $broken = '<img src="http://dixeo.local/pluginfile.php/409/mod_label/intro/0/dixeo-gen-x.png" alt="" />';
        $fixed = html_helper::normalize_legacy_intro_pluginfile_urls($broken);
        $this->assertStringContainsString('@@PLUGINFILE@@/dixeo-gen-x.png', $fixed);
        $this->assertStringNotContainsString('/intro/0/', $fixed);
    }
}
