import { USER_ROLES } from "@/utils/constants/common";
import { ABS_INS_URL, ABS_STU_URL, COMMON_URL } from "@/utils/constants/routeConst";
import peopleIcon from "@/assets/images/peopleicon.svg";
import peopleIconFill from "@/assets/images/peopleiconfill.svg";
import newspaperIcon from "@/assets/images/newspapericon.svg";
import newspaperIconFill from "@/assets/images/newspapericonfill.svg";
import bookIcon from "@/assets/images/bookicon.svg";
import bookIconFill from "@/assets/images/bookIconfill.svg";
import dialogBubble from "@/assets/images/dialogbubble.svg";
import dialogBubbleFill from "@/assets/images/dialogBubblefill.svg";
import slideShow from "@/assets/images/slideshowicon.svg";
import slideShowFill from "@/assets/images/slideshowiconfill.svg";
import homeIcon from "@/assets/images/homeicon.svg";
import codingIcon from "@/assets/images/codingicon.svg";

const base_items = [
  {
    id: "instructions",
    label: "Instructions",
    children: [
      { id: "instruction", label: "Instructions", icon: newspaperIcon, iconfill: newspaperIconFill, href: COMMON_URL.STATIC.INSTRUCTION },
      { id: "examination", label: "Examination", icon: bookIcon, iconfill: bookIconFill, href: COMMON_URL.STATIC.EXAMINATION },
      { id: "faq", label: "FAQ", icon: dialogBubble, iconfill: dialogBubbleFill, href: COMMON_URL.STATIC.FAQ },
    ],
  },
]

export const items = {
  [USER_ROLES.SUPERVISOR]: [
    {
      id: "group_management",
      label: "Group Management",
      children: [
        { id: "my_groups", label: "My Groups", icon: slideShow, iconfill: slideShowFill, href: ABS_INS_URL.STATIC.MY_GROUPS },
        { id: "available_groups", label: "Available Groups", icon: peopleIcon, iconfill: peopleIconFill, href: ABS_INS_URL.STATIC.AVAILABLE_GROUPS },
      ],
    },
    ...base_items,
  ],
  [USER_ROLES.STUDENT]: [
    {
      id: "general_management",
      label: "General Management",
      children: [
        { id: "stu_home", label: "Home", icon: homeIcon, href: ABS_STU_URL.STATIC.HOME },
        { id: "stu_exercise_list", label: "Exercise", icon: codingIcon, href: ABS_STU_URL.STATIC.EXERCISE_LIST },
      ],
    },
    ...base_items
  ]
}
export const getSidebarItemsByRole = (role) => items[role]