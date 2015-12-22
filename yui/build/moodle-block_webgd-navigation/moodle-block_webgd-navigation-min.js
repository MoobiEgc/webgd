YUI.add("moodle-block_webgd-navigation", function (e, t) {
    M.block_webgd = M.block_webgd || {}, M.block_webgd.expandablebranchcount = 1, M.block_webgd.courselimit = 20, M.block_webgd.init_add_tree = function (e) {
        e.courselimit && (this.courselimit = e.courselimit), new u(e)
    }, e.Event.define("actionkey", {
        _event: e.UA.webkit || e.UA.ie ? "keydown" : "keypress",
        _keys: {
            37: "collapse",
            39: "expand",
            32: "toggle",
            13: "enter"
        },
        _keyHandler: function (e, t, n) {
            var r;
            n.actions ? r = n.actions : r = {
                collapse: !0,
                expand: !0,
                toggle: !0,
                enter: !0
            }, this._keys[e.keyCode] && r[this._keys[e.keyCode]] && (e.action = this._keys[e.keyCode], t.fire(e))
        },
        on: function (e, t, n) {
            t.args === null ? t._detacher = e.on(this._event, this._keyHandler, this, n, {
                actions: !1
            }) : t._detacher = e.on(this._event, this._keyHandler, this, n, t.args[0])
        },
        detach: function (e, t) {
            t._detacher.detach()
        },
        delegate: function (e, t, n, r) {
            t.args === null ? t._delegateDetacher = e.delegate(this._event, this._keyHandler, r, this, n, {
                actions: !1
            }) : t._delegateDetacher = e.delegate(this._event, this._keyHandler, r, this, n, t.args[0])
        },
        detachDelegate: function (e, t) {
            t._delegateDetacher.detach()
        }
    });
    var n = 0,
            r = 20,
            i = 30,
            s = 40,
            o = {
                ROOTNODE: 0,
                SYSTEM: 1,
                CATEGORY: 10,
                MYCATEGORY: 11,
                COURSE: 20,
                SECTION: 30,
                ACTIVITY: 40,
                RESOURCE: 50,
                CUSTOM: 60,
                SETTING: 70,
                SITEADMIN: 71,
                USER: 80,
                CONTAINER: 90
            },
    u = function () {
        u.superclass.constructor.apply(this, arguments)
    };
    u.prototype = {
        id: null,
        branches: [],
        initializer: function (t) {
            this.id = parseInt(t.id, 10);
            var n = e.one("#inst" + t.id);
            if (n === null)
                return;
            e.delegate("click", this.toggleExpansion, n.one(".block_tree"), ".tree_item.branch", this), e.delegate("actionkey", this.toggleExpansion, n.one(".block_tree"), ".tree_item.branch", this);
            var r = [];
            t.expansions ? r = t.expansions : window["navtreeexpansions" + t.id] && (r = window["navtreeexpansions" + t.id]);
            for (var i in r) {
                var s = (new BRANCH({
                    tree: this,
                    branchobj: r[i],
                    overrides: {
                        expandable: !0,
                        children: [],
                        haschildren: !0
                    }
                })).wire();
                M.block_webgd.expandablebranchcount++, this.branches[s.get("id")] = s
            }
            if (window.siteadminexpansion) {
                var o = (new BRANCH({
                    tree: this,
                    branchobj: window.siteadminexpansion,
                    overrides: {
                        expandable: !0,
                        children: [],
                        haschildren: !0
                    }
                })).wire();
                M.block_webgd.expandablebranchcount++, this.branches[o.get("id")] = o;
                var u = o.node.get("childNodes").item(0);
                if (u) {
                    var a = e.Node.create('<span tabindex="0">' + u.get("innerHTML") + "</span>");
                    o.node.replaceChild(a, u)
                }
            }
            M.block_webgd.expandablebranchcount > 0 && (e.delegate("click", this.fire_branch_action, n.one(".block_tree"), ".tree_item.branch[data-expandable]", this), e.delegate("actionkey", this.fire_branch_action, n.one(".block_tree"), ".tree_item.branch[data-expandable]", this))
        },
        fire_branch_action: function (e) {
            var t = e.currentTarget.getAttribute("id"),
                    n = this.branches[t];
            n.ajaxLoad(e)
        },
        toggleExpansion: function (e) {
            if (!e.target.test("a") || e.keyCode !== 0 && e.keyCode !== 13) {
                var t = e.target;
                t.test("li") || (t = t.ancestor("li"));
                if (!t)
                    return;
                if (!t.hasClass("depth_1"))
                    if (e.type === "actionkey") {
                        switch (e.action) {
                            case "expand":
                                t.removeClass("collapsed"), t.set("aria-expanded", !0);
                                break;
                            case "collapse":
                                t.addClass("collapsed"), t.set("aria-expanded", !1);
                                break;
                            default:
                                t.toggleClass("collapsed"), t.set("aria-expanded", !t.hasClass("collapsed"))
                        }
                        e.halt()
                    } else
                        t.toggleClass("collapsed"), t.set("aria-expanded", !t.hasClass("collapsed"));
                return this.get("accordian") && t.siblings("li").each(function () {
                    this.get("id") !== t.get("id") && !this.hasClass("collapsed") && (this.addClass("collapsed"), this.set("aria-expanded", !1))
                }), this.get("candock") && M.core.dock.notifyBlockChange && M.core.dock.notifyBlockChange(this.id), !0
            }
            e.stopPropagation();
            return
        }
    }, e.extend(u, e.Base, u.prototype, {
        NAME: "navigation-tree",
        ATTRS: {
            candock: {
                validator: e.Lang.isBool,
                value: !1
            },
            accordian: {
                validator: e.Lang.isBool,
                value: !1
            },
            expansionlimit: {
                value: 0,
                setter: function (e) {
                    return e = parseInt(e, 10), e !== n && e !== r && e !== i && e !== s && (e = n), e
                }
            }
        }
    }), BRANCH = function () {
        BRANCH.superclass.constructor.apply(this, arguments)
    }, BRANCH.prototype = {
        node: null,
        initializer: function (t) {
            var r, i;
            if (t.branchobj !== null) {
                for (r in t.branchobj)
                    this.set(r, t.branchobj[r]);
                i = this.get("children"), this.set("haschildren", i.length > 0)
            }
            if (t.overrides !== null)
                for (r in t.overrides)
                    this.set(r, t.overrides[r]);
            this.node = e.one("#" + this.get("id"));
            var o = this.get("tree").get("expansionlimit"),
                    u = this.get("type");
            o !== n && u >= o && u <= s && (this.set("expandable", !1), this.set("haschildren", !1))
        },
        draw: function (t) {
            var n = this.get("expandable") || this.get("haschildren"),
                    r = e.Node.create("<li></li>"),
                    i = this.get("link"),
                    s = e.Node.create('<p class="tree_item"></p>').setAttribute("id", this.get("id"));
            i || s.setAttribute("tabindex", "0"), n && (r.addClass("collapsed").addClass("contains_branch"), r.set("aria-expanded", !1), s.addClass("branch"));
            var u = !1,
                    a = this.get("icon");
            if (a && (!n || this.get("type") === o.ACTIVITY)) {
                u = e.Node.create('<img alt="" />'), u.setAttribute("src", M.util.image_url(a.pix, a.component)), r.addClass("item_with_icon"), a.alt && u.setAttribute("alt", a.alt), a.title && u.setAttribute("title", a.title);
                if (a.classes)
                    for (var f in a.classes)
                        u.addClass(a.classes[f])
            }
            if (!i) {
                var l = e.Node.create("<span></span>");
                u && l.appendChild(u), l.append(this.get("name")), this.get("hidden") && l.addClass("dimmed_text"), s.appendChild(l)
            } else {
                var c = e.Node.create('<a title="' + this.get("title") + '" href="' + i + '"></a>');
                u && c.appendChild(u), c.append(this.get("name")), this.get("hidden") && c.addClass("dimmed"), s.appendChild(c)
            }
            return r.appendChild(s), t.appendChild(r), this.node = s, this
        },
        wire: function () {
            return this.node = this.node || e.one("#" + this.get("id")), this.node ? (this.get("expandable") && (this.node.setAttribute("data-expandable", "1"), this.node.setAttribute("data-loaded", "0")), this) : this
        },
        getChildrenUL: function () {
            var t = this.node.next("ul");
            return t || (t = e.Node.create("<ul></ul>"), this.node.ancestor().append(t)), t
        },
        ajaxLoad: function (t) {
            t.type === "actionkey" && t.action !== "enter" ? t.halt() : t.stopPropagation();
            if (t.type === "actionkey" && t.action === "enter" && t.target.test("A"))
                return this.node.setAttribute("data-expandable", "0"), this.node.setAttribute("data-loaded", "1"), !0;
            if (this.node.hasClass("loadingbranch"))
                return !0;
            if (this.node.getAttribute("data-loaded") === "1")
                return !0;
            this.node.addClass("loadingbranch");
            var n = {
                elementid: this.get("id"),
                id: this.get("key"),
                type: this.get("type"),
                sesskey: M.cfg.sesskey,
                instance: this.get("tree").get("instance")
            },
            r = "/lib/ajax/getnavbranch.php";
            return this.get("type") === o.SITEADMIN && (r = "/lib/ajax/getsiteadminbranch.php"), e.io(M.cfg.wwwroot + r, {
                method: "POST",
                data: build_querystring(n),
                on: {
                    complete: this.ajaxProcessResponse
                },
                context: this
            }), !0
        },
        ajaxProcessResponse: function (t, n) {
            this.node.removeClass("loadingbranch"), this.node.setAttribute("data-loaded", "1");
            try {
                var r = e.JSON.parse(n.responseText);
                if (r.children && r.children.length > 0) {
                    var i = 0;
                    for (var s in r.children)
                        typeof r.children[s] == "object" && (r.children[s].type === o.COURSE && i++, this.addChild(r.children[s]));
                    return (this.get("type") === o.CATEGORY || this.get("type") === o.ROOTNODE || this.get("type") === o.MYCATEGORY) && i >= M.block_webgd.courselimit && this.addViewAllCoursesChild(this), this.get("tree").get("candock") && M.core.dock.notifyBlockChange && M.core.dock.notifyBlockChange(this.get("tree").id), !0
                }
            } catch (u) {
            }
            return this.node.replaceClass("branch", "emptybranch"), !0
        },
        addChild: function (e) {
            var t = new BRANCH({
                tree: this.get("tree"),
                branchobj: e
            });
            if (t.draw(this.getChildrenUL())) {
                this.get("tree").branches[t.get("id")] = t, t.wire();
                var n = 0,
                        r, i = t.get("children");
                for (r in i)
                    i[r].type === o.COURSE && n++, typeof i[r] == "object" && t.addChild(i[r]);
                (t.get("type") === o.CATEGORY || t.get("type") === o.MYCATEGORY) && n >= M.block_webgd.courselimit && this.addViewAllCoursesChild(t)
            }
            return !0
        },
        addViewAllCoursesChild: function (e) {
            var t = null;
            e.get("type") === o.ROOTNODE ? e.get("key") === "mycourses" ? t = M.cfg.wwwroot + "/my" : t = M.cfg.wwwroot + "/course/index.php" : t = M.cfg.wwwroot + "/course/index.php?categoryid=" + e.get("key"), e.addChild({
                name: M.str.moodle.viewallcourses,
                title: M.str.moodle.viewallcourses,
                link: t,
                haschildren: !1,
                icon: {
                    pix: "i/navigationitem",
                    component: "moodle"
                }
            })
        }
    }, e.extend(BRANCH, e.Base, BRANCH.prototype, {
        NAME: "navigation-branch",
        ATTRS: {
            tree: {
                writeOnce: "initOnly",
                validator: e.Lang.isObject
            },
            name: {
                value: "",
                validator: e.Lang.isString,
                setter: function (e) {
                    return e.replace(/\n/g, "<br />")
                }
            },
            title: {
                value: "",
                validator: e.Lang.isString
            },
            id: {
                value: "",
                validator: e.Lang.isString,
                getter: function (e) {
                    return e === "" && (e = "expandable_branch_" + M.block_webgd.expandablebranchcount, M.block_webgd.expandablebranchcount++), e
                }
            },
            key: {
                value: null
            },
            type: {
                value: null,
                setter: function (e) {
                    return parseInt(e, 10)
                }
            },
            link: {
                value: !1
            },
            icon: {
                value: !1,
                validator: e.Lang.isObject
            },
            expandable: {
                value: !1,
                validator: e.Lang.isBool
            },
            hidden: {
                value: !1,
                validator: e.Lang.isBool
            },
            haschildren: {
                value: !1,
                validator: e.Lang.isBool
            },
            children: {
                value: [],
                validator: e.Lang.isArray
            }
        }
    })
}, "@VERSION@", {
    requires: ["base", "io-base", "node", "event-synthetic", "event-delegate", "json-parse"]
});