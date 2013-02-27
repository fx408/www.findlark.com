package jrgo

import (
	"fmt"
	"log"
	"reflect"
	"strings"
)

type controllerMap struct {
	controllerName string
	controllerType reflect.Type
}
type Routing struct {
	JrBase
	maps []*controllerMap
}

/*
type RoutingInterface interface {
	Register(action string, c ControllerInterface)
	Call(path string)
}
*/
func init() {
	//JrRouting.Register("gii.default", c)
}

/**
 * 路由注册，根据ControllerName定位具体的Controller
 */
func (this *Routing) Register(c ControllerInterface) {
	rt := reflect.Indirect(reflect.ValueOf(c))
	t := rt.Type()

	name := rt.FieldByName("Name").String()
	fmt.Println("->add action:", name)

	if name != "" {
		m := &controllerMap{strings.ToLower(name), t}
		this.maps = append(this.maps, m)
	}
}

/** 
 * 根据请求URL的路径，调用相应方法
 */
func (this *Routing) Call(path string, ctx *Request) bool {
	var (
		moduleMatch string
		moduleType  reflect.Type
		action      string
		find        bool
	)
	params, length := this.ParsePath(path)

	if length < 2 {
		moduleMatch = params[0] + ".default"
		params = append(params, "index", "index")
	} else {
		moduleMatch = params[0] + "." + strings.ToLower(params[1])
		if length < 3 {
			params = append(params, "index")
		}
	}
	fmt.Println("path:", params, length)

	for _, m := range this.maps {
		if m.controllerName == params[0] {
			moduleType = m.controllerType
			action = params[1]
			find = true
			break
		} else if m.controllerName == moduleMatch {
			moduleType = m.controllerType
			action = params[2]
			find = true
			break
		}
	}

	if find == true {
		vc := reflect.New(moduleType)
		method := vc.MethodByName("Action" + strings.Title(action))

		if method.IsValid() == true {
			init := vc.MethodByName("Init")
			in := make([]reflect.Value, 1)
			in[0] = reflect.ValueOf(ctx)
			init.Call(in)

			in = make([]reflect.Value, 0)

			vc.MethodByName("BeforeAction").Call(in)
			method.Call(in)
			vc.MethodByName("AfterAction").Call(in)
			return true
		} else {
			log.Println("Call to undefined action: ", action)
		}
	} else {
		log.Println("Call to undefined controller or module: ", params[0])
	}

	return false
}

/**
 * 解析路径，分离参数及计算参数个数
 */
func (this *Routing) ParsePath(path string) ([]string, int) {
	temp := strings.Split(path, "/")
	var params []string

	for _, v := range temp {
		if strings.Trim(v, " ") != "" {
			params = append(params, v)
		}
	}

	length := len(params)

	if length == 0 {
		params = append(params, "site")
		length = 1
	}
	params[0] = strings.ToLower(params[0])

	return params, length
}
